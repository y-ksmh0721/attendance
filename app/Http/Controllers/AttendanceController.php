<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Work;
use App\Models\Cliant;
use App\Models\User;
use App\Http\Requests\ValidateRequest;
use App\Http\Requests\AttendanceRequest;

// use Illuminate\Http\AttendanceRequest;

class AttendanceController extends Controller
{
    public function confirm(ValidateRequest $request){
        $user = $request->user();
        // $attendance = (object) $request->all();
        $attendance = $request->all();
        $human = User::find($attendance['user_id']);
        // dd($human);
        return view('attendance.confirm',[
            'attendance' => $attendance,
            'user' => $user,
            'human' => $human,
        ]);
    }

    public function complete(Request $request){
        $attendance = $request->all();

        $companyInfo = User::where('name', $attendance['name'])->first();

        $date = $request->date;
        foreach ($request->start_time as $index => $startStr) {
            $startTime = Carbon::parse($startStr); // この時点で $index は定義されている！

            // 日勤判定（08:00～17:00）
            if ($startTime->format('H:i') >= '08:00' && $startTime->format('H:i') < '17:00') {
                $alreadyNikki = Attendance::where('name', $request->name)
                    ->where('date', $date)
                    ->where('time_type', '日勤')
                    ->exists();

                if ($alreadyNikki) {
                    return redirect()->route('dashboard')->with('error', 'すでに日勤が登録されています');
                }
            }

            // 夜勤判定（20:00～翌05:00）
            if ($startTime->format('H:i') >= '20:00' || $startTime->format('H:i') < '05:00') {
                $alreadyYakin = Attendance::where('name', $request->name)
                    ->where('date', $date)
                    ->where('time_type', '夜勤')
                    ->exists();

                if ($alreadyYakin) {
                    return redirect()->route('dashboard')->with('error', 'すでに夜勤が登録されています');
                }
            }

            // 残業のみ（20:00～翌05:00）
            if ($startTime->format('H:i') >= '17:00' || $startTime->format('H:i') < '20:00') {
                $alreadyOver = Attendance::where('name', $request->name)
                    ->where('date', $date)
                    ->where('time_type', '残業のみ')
                    ->exists();

                if ($alreadyOver) {
                    return redirect()->route('dashboard')->with('error', 'すでに夜勤が登録されています');
                }
            }
        }




        // フォームで送信された配列データ
        // $sites = $attendance['site']; // 現場名
        $workContents = $attendance['work_content']; // 作業内容
        $otherWorkContents = $attendance['other_work_content'] ?? []; // 「その他」の作業内容
        $startTimes = $attendance['start_time']; // 開始時間
        $endTimes = $attendance['end_time']; // 終了時間
        $siteIds = [];

        foreach ($attendance['site'] as $siteJson) {
            $siteData = json_decode($siteJson, true); // 連想配列に変換
            if (isset($siteData['id'])) {
                $siteIds[] = $siteData['id']; // IDだけ配列に入れる
            }
        }


        // 配列の数だけループ
        foreach ($siteIds as $index => $site) {

            $siteItem = Work::find($site);
            $siteName = $siteItem->name;
            $cliant_name = $siteItem->cliant->cliant_name;


            //作業開始と終了の時間を取得
            $start = Carbon::parse($startTimes[$index]);
            $end = Carbon::parse($endTimes[$index]);
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            //休憩時間を定義
            $breakStartEvening = Carbon::parse($start->format('Y-m-d') . ' 12:00');
            $breakEndEvening   = Carbon::parse($start->format('Y-m-d') . ' 13:00');
            $breakStartNight = Carbon::parse($start->copy()->addDay()->format('Y-m-d') . ' 00:00');
            $breakEndNight   = Carbon::parse($start->copy()->addDay()->format('Y-m-d') . ' 01:00');
            // 基準時間をCarbonで定義
            $eightAM   = Carbon::parse($start->format('Y-m-d') . ' 08:00');
            $fivePM    = Carbon::parse($start->format('Y-m-d') . ' 17:00');
            $eightPM   = Carbon::parse($start->format('Y-m-d') . ' 20:00');
            $fiveAM    = Carbon::parse($start->copy()->addDay()->format('Y-m-d') . ' 05:00');


            // 追加：17:00で作業終了とする制限時間を定義
            $endLimit = Carbon::parse($start->format('Y-m-d') . ' 17:00');

            // 勤務区分によって終了時刻の調整方法を変える
            if ($start >= $eightAM && $start < $eightPM) {
                // 日勤・残業：17時までに制限
                $adjustedEnd = $end->greaterThan($endLimit) ? $endLimit : $end;
            } else {
                // 夜勤など：制限せずそのまま
                $adjustedEnd = $end;
            }


            //総作業時間（分単位）
            $workMinutes = $start->diffInMinutes($adjustedEnd);
            //休憩の被りのチェック
            if($start < $breakEndEvening && $adjustedEnd > $breakStartEvening){
                $workMinutes -= $breakStartEvening->diffInMinutes($breakEndEvening);
            }
            if($start < $breakEndNight && $adjustedEnd > $breakStartNight){
                $workMinutes -= $breakStartNight->diffInMinutes($breakEndNight);
            }
            //作業時間（少数で保存）
            $workTime = round($workMinutes / 60 ,2);
            if($workTime > 8){
                $workTime = 8.0;
            }

            //人役（作業時間 × 0.125）
            $humanRole = round($workTime*0.125,4);
            // dd($humanRole);
            // もし登録現場が1つで、作業時間が5時間以上なら人役は1にする
            if (count($siteIds) === 1 && $workTime >= 5) {
                $humanRole = 1.0;
            }

            //残業時間
            // 通常残業（17:00以降）
            $startTime = Carbon::parse($startTimes[$index]);
            $overtime = 0; // ← 必ず初期化！

            // --- 残業時間計算 ---
            $standardEnd = Carbon::parse('17:00');

            if ($end > $standardEnd) {
                // 17:45, 18:45, ...で1時間ごとにカウント
                $minutes = $standardEnd->diffInMinutes($end);
                $overtime = floor($minutes / 60); // 1時間単位
                if ($minutes % 60 >= 45) {
                    $overtime += 1;
                }
            }

            if ($startTime >= $eightAM && $startTime < $fivePM) {
                $timeType = '日勤';
            } elseif ($startTime >= $fivePM && $startTime < $eightPM) {
                $timeType = '残業のみ';
                $workTime = 0;
                $humanRole = 0;
            } elseif ($startTime >= $eightPM || $startTime < $fiveAM) {
                $timeType = '夜勤';
                $humanRole = 1;
                $overtime = 0;
            } else {
                $timeType = '不明';
            }



            // 「作業内容」が「その他」の場合、テキストボックスの値を優先
                $workContent = ($workContents[$index] == 'その他' && isset($otherWorkContents[$index]))
                    ? $otherWorkContents[$index]
                    : $workContents[$index];

            // 労務 or 外注 の変換
                $workType = ($attendance['work_type'] == '労務') ? '請負' : '外注';


                // dd($attendance['count']);

            // 出勤データを作成
            $newAtt = new Attendance();
            $newAtt->name = $attendance['name'];  //名前
            $newAtt->company = $companyInfo->company; //所属
            $newAtt->work_type = $workType;       //種別ok
            $newAtt->date = $attendance['date'];  //日付ok
            $newAtt->count = $attendance['count'];
            $newAtt->site_id = $site;             //現場ok
            $newAtt->site = $siteName;             //現場名ok
            $newAtt->cliant = $cliant_name;             //クライアント名ok
            $newAtt->work_content = $workContent;//作業内容ok
            $newAtt->start_time = $startTimes[$index]; //開始時間ok
            $newAtt->end_time = $endTimes[$index]; //終了時間ok
            $newAtt->work_time = $workTime;       //作業時間
            $newAtt->human_role = $humanRole;    //人役
            $newAtt->time_type = $timeType;      //勤務タイプ
            $newAtt->overtime = $overtime;          //残業
            $newAtt->write = $request->user_id; //書き込みユーザーID
            $newAtt->save();
        }

        return view('attendance.complete', compact('attendance'));
    }


    public function list(Request $request){
        $user = $request->user();
        $userId = $user['id'];

        //リレーションにて結合したcraftとcompanyをattendanceテーブルと一緒に持ってくる
        $attendances = Attendance::with(['craft.company','work.cliant'])
                                    ->orderby('date','desc')
                                    ->orderby('name','asc');

        // フォームで送られてきた値を取得
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $keyword = $request->input('keyword');

        // 開始日と終了日がある場合
        if ($startDate && $endDate) {
            $attendances = $attendances->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $attendances = $attendances->where('date', '>=', $startDate);
        } elseif ($endDate) {
            $attendances = $attendances->where('date', '<=', $endDate);
        }


        //キーワード検索処理
        if($keyword){
            $attendances = $attendances->where(function($query) use ($keyword){
                $query->where('name', 'like', "%$keyword%")
                        ->orwhereHas('work', function($query) use ($keyword){
                            $query->where('name', 'like', "%$keyword%");
                        })
                        ->orWhereHas('work.cliant', function($query) use ($keyword){
                            $query->where('cliant_name', 'like', "%$keyword%");
                        })
                        ->orWhereHas('craft.company', function($query) use ($keyword){
                            $query->where('name', 'like', "%$keyword%");
                        });
            });
        }

        //絞り込んだデータの取得
         $attendances = $attendances->paginate(25);

        return view('attendance.list',compact('attendances','user'));
    }

    public function toggleOvertime(Request $request, $id) {
        $attendance = Attendance::findOrFail($id); // IDで出勤表を取得

        // 現在の残業時間を取得（数値として）
        $currentOvertime = (float)$attendance->overtime;

        // 残業時間の増減処理
        if ($request->has('overtime_add')) {
            $currentOvertime += 0.5;
        } elseif ($request->has('overtime_remove')) {
            $currentOvertime = max(0, $currentOvertime - 0.5); // 0未満にならないように
        }

        // 残業時間を更新
        $attendance->overtime = $currentOvertime;

        // 出勤データを保存
        $attendance->save();

        // 更新完了メッセージ
        return redirect()->back()->with('success', 'ステータスを更新しました。');
    }


    public function toggleStatus($id) {
        $attendance = Attendance::findOrFail($id); // IDで出勤表を取得

        //請負・常用の切り替えと外注の時の表示
        if ($attendance->work_type === '外注') {
            return redirect()->back()->with('error', '外注の勤務形態は変更できません。');
        }
        $attendance->work_type = ($attendance->work_type === '請負') ? '常用' : '請負';

        $attendance->save();

        return redirect()->back()->with('success', 'ステータスを更新しました。');
    }

    public function edit($id){
        // `works` テーブルのデータを取得
        $works = Work::all();
         //リレーションにて結合したcraftとcompanyをattendanceテーブルと一緒に持ってくる
         $attendance = Attendance::with(['craft.company','user'])->findOrFail($id);

        return view('attendance.edit',compact('attendance','works'));
    }

    public function update(AttendanceRequest $request, Attendance $attendance){
        //更新処理
        $attendance = Attendance::find($request->id); // id で検索
        if (!$attendance) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $site = Work::where('name', $request->site)->get();
        $siteId = $site->first()->id;

        $attendance->fill([
            'name' => $request->name,
            'date' => $request->date,
            'site_id' => $siteId,
            'start_time'=> $request->start_time,
            'end_time'=> $request->end_time,
            'work_time' => $request->work_time,
            'work_type' => $request->work_type,
            'time_type' => $request->time_type,
            'human_role' => $request->human_role
        ])->save();

        return redirect()->route('attendance.list')->with('message', 'Update Complete');
    }

    public function destroy($id)
    {
        // cliantsテーブルから指定のIDのレコード1件を取得
        $attendance = Attendance::find($id);
        // レコードを削除
        $attendance->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('attendance.list');
    }
}

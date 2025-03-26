<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Work;
use App\Http\Requests\ValidateRequest;
use App\Http\Requests\AttendanceRequest;

// use Illuminate\Http\AttendanceRequest;

class AttendanceController extends Controller
{
    public function confirm(ValidateRequest $request){
        $user = $request->user();
        // $attendance = (object) $request->all();
        $attendance = $request->all();
        return view('attendance.confirm',[
            'attendance' => $attendance,
            'user' => $user
        ]);
    }

    public function complete(Request $request){
        $attendance = $request->all();

        // 🔹 同じ人が、同じ日に「終日勤務」として登録されているかチェック
        $alreadyFullDay = Attendance::where('name', $request->name)
                    ->where('date', $request->date)
                    ->where('time_type', '終日') // time_type が「終日」ならブロック
                    ->exists();

        // 🔹 新しく登録しようとしているデータの中に「終日勤務」があるかチェック
        $inputHasFullDay = in_array('終日', $attendance['end_time']);

        // 🔹 すでに終日勤務があり、新規のデータにも終日勤務が含まれていたら登録を防ぐ
        if ($alreadyFullDay || $inputHasFullDay) {
            return redirect()->route('dashboard');
        }

        // フォームで送信された配列データ
        $sites = $attendance['site']; // 現場名
        $workContents = $attendance['work_content']; // 作業内容
        $otherWorkContents = $attendance['other_work_content'] ?? []; // 「その他」の作業内容
        $endTimes = $attendance['end_time']; // 終了時間

        // 🔹 配列の数だけループ
        foreach ($sites as $index => $site) {
            // 「作業内容」が「その他」の場合、テキストボックスの値を優先
            $workContent = ($workContents[$index] == 'その他' && isset($otherWorkContents[$index]))
                ? $otherWorkContents[$index]
                : $workContents[$index];

            // 「終日 or 半日」の判定
            $timeType = ($endTimes[$index] < '14:59:59' || count($endTimes) > 1) ? '半日' : '終日';

            // 労務 or 外注 の変換
            $workType = ($attendance['work_type'] == '労務') ? '請負' : '外注';

            // 出勤データを作成
            $newAtt = new Attendance();
            $newAtt->name = $attendance['name'];
            $newAtt->work_type = $workType;
            $newAtt->date = $attendance['date'];
            $newAtt->site = $site;
            $newAtt->work_content = $workContent;
            $newAtt->end_time = $endTimes[$index];
            $newAtt->time_type = $timeType;
            $newAtt->write = $request->user_id;
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
                        ->orwhere('site', 'like', "%$keyword%")
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
         $attendance = Attendance::with(['craft.company'])->findOrFail($id);

        return view('attendance.edit',compact('attendance','works'));
    }

    public function update(AttendanceRequest $request, Attendance $attendance){
        //更新処理
        $attendance = Attendance::find($request->id); // id で検索
        if (!$attendance) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // dd($request->work_type);

        $attendance->fill([
            'name' => $request->name,
            'date' => $request->date,
            'site' => $request->site,
            'end_time'=> $request->end_time,
            'work_type' => $request->work_type,
            'time_type' => $request->time_type,
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

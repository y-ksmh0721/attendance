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


        // 現場と作業内容が配列で送信される
        $sites = $attendance['site']; // 現場名の配列
        $workContents = $attendance['work_content']; // 作業内容の配列
        $otherWorkContents = $attendance['other_work_content']; // 「その他」の作業内容（テキスト入力）


    foreach ($sites as $index => $site) {
        // 作業内容が「その他」の場合、テキストボックスの内容を使用
        $workContent = $workContents[$index] == 'その他' ? $otherWorkContents[$index] : $workContents[$index];

        // 新しい出勤データを作成
        $newAtt = new Attendance();
        $newAtt->name = $attendance['name'];
        $newAtt->work_type = $attendance['work_type'];
        $newAtt->date = $attendance['date'];
        $newAtt->site = $site;
        $newAtt->work_content = $workContent;
        $newAtt->end_time = $attendance['end_time'];
        $newAtt->write = $request->user_id;
        $newAtt->save();
    }

    return view('attendance.complete', compact('attendance'));
}

    public function list(Request $request){
        $user = $request->user();
        $userId = $user['id'];

        //リレーションにて結合したcraftとcompanyをattendanceテーブルと一緒に持ってくる

        $attendances = Attendance::with(['craft.company','work.cliant'])->orderby('date','desc');

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
                        ->orWhereHas('craft.company', function($query) use ($keyword){
                            $query->where('name', 'like', "%$keyword%");
                        });
            });
        }

        //絞り込んだデータの取得
         $attendances = $attendances->get();

        //attendanceテーブルの日付を曜日に変換する
        foreach($attendances as $attendance){
            $attendance->day_of_week = Carbon::parse($attendance->date)->locale('ja')->isoFormat('ddd');
        }

        return view('attendance.list',compact('attendances','user'));
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

        $attendance->fill([
            'date' => $request->date,
            'name' => $request->name,
            'morning_site' => $request->morning_site,
            'afternoon_site' => $request->afternoon_site,
            'overtime' => $request->overtime
        ])->save();

        return redirect()->route('attendance.list')->with('message', 'Update Complete');
    }
}

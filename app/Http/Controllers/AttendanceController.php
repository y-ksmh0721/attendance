<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Work;

// use Illuminate\Http\AttendanceRequest;

class AttendanceController extends Controller
{
    public function confirm(Request $request){
        // $attendance = (object) $request->all();
        $attendance = $request->all();
        return view('attendance.confirm',['attendance' => $attendance]);
    }

    public function complete(Request $request){
        $attendance = $request->all();

        $newAtt = new Attendance();
        $newAtt->date = $attendance['date'];
        $newAtt->name = $attendance['name'];
        $newAtt->morning_site = $attendance['morning_site'];
        $newAtt->afternoon_site = $attendance['afternoon_site'];
        $newAtt->overtime = $attendance['overtime'];
        $newAtt->save();



        return view('attendance.complete',compact('attendance'));
    }

    public function list(Request $request){
        $user = $request->user();
        $userId = $user['id'];

        //リレーションにて結合したcraftとcompanyをattendanceテーブルと一緒に持ってくる
        $attendances = Attendance::with(['craft.company'])->get();
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

    public function update(Request $request, Attendance $attendance){
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

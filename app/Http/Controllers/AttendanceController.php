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

        //フォームで送られてきた値取得
        // $date = $request->input('date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $keyword = $request->input('keyword');

        //リレーションにて結合したcraftとcompanyをattendanceテーブルと一緒に持ってくる
        $attendances = Attendance::with(['craft.company'])->orderby('date','desc');

        // if($date){
        //     $attendance = $attendances->where('date',$date);
        // }

        // 開始日と終了日がある場合
        if ($startDate && $endDate) {
            $attendances = $attendances->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $attendances = $attendances->where('date', '>=', $startDate);
        } elseif ($endDate) {
            $attendances = $attendances->where('date', '<=', $endDate);
        }

        if($keyword){
            $attendances = $attendances->where(function($query) use ($keyword){
                $query->where('name', 'like', "%$keyword%")
                        ->orwhere('morning_site', 'like', "%$keyword%")
                        ->orwhere('afternoon_site', 'like', "%$keyword%")
                        ->orWhereHas('craft.company', function($query) use ($keyword){
                            $query->where('name', 'like', "%$keyword%");
                        });
            });
        }

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
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

        $attendance = Attendance::select('date',Attendance::raw('DAYOFWEEK(date) as dow'),'name','morning_site','afternoon_site','overtime')
                      ->orderby('date','desc')
                      ->get();

        return view('attendance.list',[
            'user'=>$user,
            'attendance'=>$attendance
        ]);
    }
}

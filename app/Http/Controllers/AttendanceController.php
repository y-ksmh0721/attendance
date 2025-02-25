<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
// use Illuminate\Http\AttendanceRequest;

class AttendanceController extends Controller
{
    public function confirm(Request $request){
        $attendance = (object) $request->all();
        return view('attendance.confirm',['attendance' => $attendance]);
    }

    public function complete(Request $request){
        $attendance = $request->all();
        $user = $request->user();

        $newAtt = new Attendance();
        $newAtt->date = $request->date;
        $newAtt->user_id = $user->id;
        $newAtt->morning_site = $request->morning_site;
        $newAtt->afternoon_site = $request->afternoon_site;
        $newAtt->overtime = $request->overtime;
        $newAtt->save();



        return view('attendance.complete',compact('attendance'));
    }

    public function list(Request $request){
        $user = $request->user();

        $attendance = Attendance::select('date',Attendance::raw('DAYOFWEEK(date) as dow'),'morning_site','afternoon_site')
                      ->where('user_id',$user->id)
                      ->orderby('date','desc')
                      ->get();

        return view('attendance.list',[
            'user'=>$user,
            'attendance'=>$attendance
        ]);
    }
}

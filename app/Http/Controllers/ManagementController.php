<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliant;
use App\Models\Attendance;
use App\Models\Work;

class ManagementController extends Controller
{
    public function index(){
        $attendance = Attendance::with('user')
        ->orderByDesc('created_at')
        ->get();
        // dd($attendance);

        return view('management.management',[
            'attendance'=>$attendance
        ]);
    }

    public function confirm(Request $request){
        $cliant = (object) $request->all();


        return view('management.confirm', ['cliant' => $cliant]);
    }

    public function complete(Request $request){
        $cliant = (object) $request->all();

        $cliants = new Cliant();
        $cliants->cliant_name = $cliant->cliant_name;
        $cliants->save();


        return view('management.complete', ['cliant' => $cliant]);
    }

    public function edit($id){
        $attendance = Attendance::find($id);
        $works = Work::all();
        return view('management.edit',compact('attendance','works'));
    }

    public function update(Request $request, Attendance $attendance){
        //更新処理

        $attendance = Attendance::find($request->id); // id で検索
        if (!$attendance) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $attendance->fill([
            'date' => $request->date,
            'morning_site' => $request->morning_site,
            'afternoon_site' => $request->afternoon_site,
            'user_id'=> $request->user_id,
            'overtime' => $request->overtime
        ])->save();

        return response()->json(['message' => 'Update Complete']);
    }

    public function destroy($id)
    {
        // Booksテーブルから指定のIDのレコード1件を取得
        $cliant = Cliant::find($id);
        // レコードを削除
        $cliant->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('management.management');

    }


}

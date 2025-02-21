<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliant;

class ManagementController extends Controller
{
    public function index(Request $request){
        
        return view('management.management',);
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

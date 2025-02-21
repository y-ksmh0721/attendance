<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliant;

class CliantController extends Controller
{
    public function list(){
        $cliants = Cliant::all()->toArray();
        return view('cliant.list', ['cliants'=>$cliants ]);
    }

    public function destroy($id)
    {
        // cliantsテーブルから指定のIDのレコード1件を取得
        $cliant = Cliant::find($id);
        // レコードを削除
        $cliant->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('cliant.list');
    }
}

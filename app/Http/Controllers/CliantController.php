<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliant;
use App\Http\Requests\CliantRequest;

class CliantController extends Controller
{
    public function list(){
        $cliants = Cliant::all()->toArray();
        return view('cliant.list', ['cliants'=>$cliants ]);
    }

    public function confirm(CliantRequest $request){
        $cliant = (object) $request->all();

        return view('cliant.confirm', ['cliant' => $cliant]);
    }

    public function complete(Request $request){
        $cliant = (object) $request->all();

        $cliants = new Cliant();
        $cliants->cliant_name = $cliant->cliant_name;
        $cliants->save();

        return redirect()->route('cliant.list');
        // return view('cliant.complete', ['cliant' => $cliant]);
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

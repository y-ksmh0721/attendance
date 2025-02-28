<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Cliant;
use App\Http\Requests\WorkRequest;

class WorkController extends Controller
{
    public function index(Request $request){
        //ドロップダウン
        $cliants = Cliant::all();

        $works = Work::with('cliant')
        ->orderByDesc('created_at')
        ->get();
        // dd($works);
        return view('works.index', compact('works','cliants'));
    }

    public function confirm(WorkRequest $request) {
        $work = (object) $request->only(['site_name']);
        
        $cliant = $request->all();
        if (isset($cliant['cliant_info'])) {
            $cliant['cliant_info'] = json_decode($cliant['cliant_info'], true);
        }
        return view('works.confirm', compact('work', 'cliant'));
    }

    public function complete(Request $request){
        $work = (object) $request->only(['site_name']);
        $request = $request->all();

        //DBへ保存処理
        $works = new Work();
        $works->name = $request['site_name'];
        $works->cliant_id = $request['cliant_id'];
        $works->save();

        return redirect()->route('works.index');
    }

    public function toggleStatus($id) {
        $work = Work::findOrFail($id); // IDで現場を取得

        // ステータスを切り替える
        $work->status = ($work->status === 'active') ? 'inactive' : 'active';
        $work->save();

        return redirect()->back()->with('success', 'ステータスを更新しました。');
    }

    public function destroy($id)
    {
        // cliantsテーブルから指定のIDのレコード1件を取得
        $work = Work::find($id);
        // レコードを削除
        $work->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('works.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Cliant;

class WorkController extends Controller
{
    public function index(Request $request){
        $works = Work::with('cliant')->orderByDesc('created_at')->get();
        $cliants = Cliant::all();
        

        return view('works.index', compact('works','cliants'));
    }

    // public function confirm(Request $request){
    //     $work = (object) $request->only(['site_name']);
    //     $cliant = (object) $request->all();
    //     dd($cliant);
    //     return view('works.confirm', ['work' => $work,'cliant'=>$cliant]);
    // }
    // public function confirm(Request $request) {
    //     $work = (object) $request->only(['site_name']);
    //     $cliant = Cliant::find($request->id);
    //     return view('works.confirm', ['work' => $work, 'cliant' => $cliant]);
    // }

    public function confirm(Request $request) {
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


        return view('works.complete',compact('work'));
    }

    public function toggleStatus($id) {
        $work = Work::findOrFail($id); // IDで現場を取得

        // ステータスを切り替える
        $work->status = ($work->status === 'active') ? 'inactive' : 'active';
        $work->save();

        return redirect()->back()->with('success', 'ステータスを更新しました。');
    }
}

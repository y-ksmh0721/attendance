<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Craft;
use App\Models\Company;
use App\Http\Requests\CraftRequest;

class CraftController extends Controller
{
    public function index(Request $request){
        //ドロップダウン
        $companys = Company::all();
        //リスト
        $craft = Craft::with('company');

        //フォームで送られた値
        $keyword = $request->input('keyword');

        if($keyword){
            $craft = $craft->where(function($query) use ($keyword){
                $query->where('name', 'like', "%$keyword%")
                      ->orWhereHas('company', function($query) use ($keyword){
                        $query->where('name', 'like', "%$keyword%");
                      });
            });
        }

        //絞り込んだデータの取得
        $craft = $craft->get();

        return view('craft.index', [
            'companys' => $companys,
            'craft' => $craft
        ]);
    }

    public function confirm(CraftRequest $request) {
        $craft = (object) $request->only(['craft_name']);
        $company = $request->all();
        if (isset($company['company_info'])) {
                $company['company_info'] = json_decode($company['company_info'], true);
            }
            // dd($company);
        return view('craft.confirm', compact('craft', 'company'));
    }

    public function complete(Request $request){
        $company = (object) $request->only(['company_name']);
        $request = $request->all();

        //DBへ保存処理
        $craft = new Craft();
        $craft->name = $request['craft_name'];
        $craft->company_id = $request['company_id'];
        $craft->save();

        return redirect()->route('craft.index');
    }

    public function toggleStatus($id) {
        $craft = Craft::findOrFail($id); // IDで現場を取得

        // ステータスを切り替える
        $craft->status = ($craft->status === 'active') ? 'inactive' : 'active';
        $craft->save();

        return redirect()->back()->with('success', 'ステータスを更新しました。');
    }

    public function destroy($id)
    {
        // cliantsテーブルから指定のIDのレコード1件を取得
        $craft = Craft::find($id);
        // レコードを削除
        $craft->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('craft.index');
    }
}

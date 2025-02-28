<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\CompanyRequest;


class CompanyController extends Controller
{
    public function list(){
        $companys = Company::all()->toArray();
        return view('company.list', ['companys'=>$companys ]);
    }

    public function confirm(CompanyRequest $request){
        $company = $request->company_name;

        return view('company.confirm',['company' => $company]);
    }


    public function complete(Request $request){
        $company = new Company();
        $company->name = $request->company_name;
        $company->save();

        // return view('company.complete',['company' => $company]);
        return redirect()->route('company.list');
    }

    public function destroy($id)
    {
        //cliantsテーブルから指定のIDのレコード1件を取得
        $company = Company::find($id);
        // レコードを削除
        $company->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('company.list');
    }
}

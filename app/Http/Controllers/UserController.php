<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function list(Request $request){
        $users = User::orderByRaw("CASE WHEN company = \"Y's tec\" THEN 0 ELSE 1 END")
             ->orderBy('is_allowed', 'desc')
             ->orderBy('permission', 'asc') // 数字が小さい人を上に
             ->orderBy('name', 'asc')
             ->get();


        $user = $request->user();
        return view('users.list',compact('users','user'));
    }

    public function toggle(User $user)
    {
        $user->is_allowed = $user->is_allowed == '1' ? '0' : '1';
        $user->save();

        return redirect()->back()->with('message', 'ログイン権限を更新しました');
    }

    public function edit($id){
        $user = User::all()->find($id);

        return view('users.edit', [
            'user' => $user
        ]);
    }

        public function update(Request $request){
        //更新処理
        $user = User::find($request->id); // id で検索
        if (!$user) {
            return response()->json(['error' => 'Record not found'], 404);
        }
                $user->fill([
            'name' => $request->name,
            'permission' => $request->role
        ])->save();

        return redirect()->route('user.list')->with('message', 'Update Complete');
                }
}

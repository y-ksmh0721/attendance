<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function list(){
        $users = User::all();
        return view('users.list',compact('users'));
    }

    public function toggle(User $user)
    {
        $user->is_allowed = $user->is_allowed == '1' ? '0' : '1';
        $user->save();

        return redirect()->back()->with('message', 'ログイン権限を更新しました');
    }
}

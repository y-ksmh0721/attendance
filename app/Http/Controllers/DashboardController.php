<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Craft;
use App\Models\Cliant;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $works = Work::all(); // `works` テーブルのデータを取得
        $crafts = Craft::all();
        $cliant = Cliant::all();
        $allUser = User::all();

        return view('dashboard', compact('works','crafts','user','cliant','allUser'));
    }
}

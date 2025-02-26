<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Craft;

class DashboardController extends Controller
{
    public function index()
    {
        $works = Work::all(); // `works` テーブルのデータを取得
        $crafts = Craft::all();


        return view('dashboard', compact('works','crafts'));
    }
}

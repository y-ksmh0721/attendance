<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;

class DashboardController extends Controller
{
    public function index()
    {
        $works = Work::all(); // `works` テーブルのデータを取得

        return view('dashboard', compact('works'));
    }
}

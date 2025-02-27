<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliant;
use App\Models\Attendance;
use App\Models\Work;

class ManagementController extends Controller
{
    public function index(){
        return view('management.management');
    }

}

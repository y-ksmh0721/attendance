<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliant;

class ManagementController extends Controller
{
    public function index(Request $request){
        $cliants = Cliant::all()->toArray();
        return view('management.management',['cliants'=>$cliants ]);
    }

    public function confirm(Request $request){
        $cliant = (object) $request->all();


        return view('management.confirm', ['cliant' => $cliant]);
    }

    public function complete(Request $request){
        $cliant = (object) $request->all();

        $cliants = new Cliant();
        $cliants->cliant_name = $cliant->cliant_name;
        $cliants->save();


        return view('management.complete', ['cliant' => $cliant]);
    }
}

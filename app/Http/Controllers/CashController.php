<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashController extends Controller
{
    public function cash(){
        return view('Pages.CashTransaction');
    }
}

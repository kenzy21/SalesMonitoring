<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockReceiveController extends Controller
{
    public function stockreceive(){
        return view('Pages.StockReceive');
    }
}

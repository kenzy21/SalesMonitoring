<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MasterFileTraits;

class StockReceiveController extends Controller
{
    use MasterFileTraits;

    public function stockreceive(){
        return view('Pages.StockReceive');
    }

    public function stockreceivecreate(){
        return view('Pages.StockReceiveCreate');
    }
}

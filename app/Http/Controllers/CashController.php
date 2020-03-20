<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    public function cash(){

        $discounts = DB::table("salesmonitoring.discount")
                     ->select(DB::raw("CONCAT(discount_type,'-',discount_desc) discount_"))
                     ->get();
        return view('Pages.CashTransaction',compact('discounts'));
    }
}

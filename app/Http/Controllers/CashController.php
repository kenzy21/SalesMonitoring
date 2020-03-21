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

    public function savecashtransaction(Request $request){
        $transactionlist = $request->transactionlist;
        $discounttype = $request->discounttype;
        $grossamount = $request->grossamount;
        $discount = $request->discount;
        $netamount = $request->netamount;
        
        return response()->json(["message"=>"success","transactionlist"=>$transactionlist]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Masterfile;
use Illuminate\Support\Facades\DB;
use App\Discount;
use App\Http\Traits\MasterfileTraits;

class ReferenceController extends Controller
{
    use MasterfileTraits;

    public function GetListClients(){
        $clients = Client::all('client_code','client_name');
        return response()->json(["clients"=>$clients]);
    }

    public function GetListItems(){
        $stocklist = DB::table("salesmonitoring.masterfile")
                    ->select("stockcode","stockdesc","unit",
                        DB::raw("FORMAT(((SELECT markup FROM salesmonitoring.markup WHERE stocktag = masterfile.stocktag) * currcost),2) price"))
                    ->get();
        return response()->json(["stocklist"=>$stocklist]);
    }

    public function GetDiscountType(Request $request){
        $disctype = $request->discounttype;

        $discount = Discount::where("discount_type",$disctype)->get();

        $discount_type = $this->ConvertCollectionToArray($discount,"percentage");

        $discount_amount = $this->ConvertCollectionToArray($discount,"discount");

        return response()->json(["discounttype"=>$discount_type,"discount"=>$discount_amount]);
    }
}

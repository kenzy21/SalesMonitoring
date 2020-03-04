<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MasterfileTraits;
use App\Masterfile;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    use MasterfileTraits;

    public function test(){
        // $classification = Masterfile::all();

        // $classification = DB::table('masterfile')->get();
        // dd($classification);

        //$test = Masterfile::where("stockcode","5")->first('stockdesc');

        //dd($test->stockdesc);

                $jsonobj = '[{"stockcode":"1","unit":"PCS","qty":"1","cost":"1,000.00","amount":"1,000.00"},{"stockcode":"14","unit":"PCS","qty":"80","cost":"100.00","amount":"8,000.00"}]';

                $arr = json_decode($jsonobj,true);

                foreach ($arr as $ar => $value) {
                    $converted = str_replace(",","",$value["cost"]);
                    echo $converted . "<br>";
                }



    }
}

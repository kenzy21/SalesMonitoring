<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MasterFileTraits;
use carbon\carbon;
use App\StockreceiveHeader;
use App\StockreceiveDetails;
use App\PurchaseorderHeader;
use Illuminate\Support\Facades\DB;

class StockReceiveController extends Controller
{
    use MasterFileTraits;

    public function stockreceive(){

        $rrheaders = $this->GetStockReceiveHeader("","","rrall");
        
        return view('Pages.StockReceive',compact('rrheaders'));
    }

    public function stockreceivecreate(){
        return view('Pages.StockReceiveCreate');
    }

    public function stockreceiveperiod(Request $request){
        $dtfrom = $request->dtfrom;
        $dtto = $request->dtto;
        $rrtype = $request->rrtype;

        $rrheader = $this->GetStockReceiveHeader($dtfrom,$dtto,$rrtype);

        return response()->json(["message"=>"success","rrheader"=>json_encode($rrheader)]);
    }

    public function IsSerialize(Request $request){
        $stockcode = $request->stockcode;
        $check = $this->CheckSerialized($stockcode);

        return response()->json(["check"=>$check]);
    }

    public function stockreceivedetails(Request $request){

        $rrcode = $request->rrcode;
        $querytype = $request->querytype;

        $rrdetails = $this->GetStockReceiveDetails($rrcode);

        return response()->json(["message"=>"success","rrdetails"=>json_encode($rrdetails)]);
    }

    public function stockreceivesavetransaction(Request $request){
        $pocode = $request->pocode;
        $remarks = $request->remarks;
        $grossamount = str_replace(",","",$request->gross_amount);
        $discountamount = str_replace(",","",$request->discount_amount);
        $netamount = str_replace(",","",$request->net_amount);
        $rrdetails = json_decode($request->stockreceive_details,true);
        $rr_date = carbon::now();

        try{

            DB::beginTransaction();

            $rr_header = new StockreceiveHeader;

            $rr_header->stockreceive_prefix = $this->ConvertDateforPrefix($rr_date);
            $rr_header->stockreceive_date = $rr_date;
            $rr_header->purchaseorder_code = $pocode;
            $rr_header->gross_amount = $grossamount;
            $rr_header->discount_amount = $discountamount;
            $rr_header->net_amount = $netamount;
            $rr_header->remarks = $remarks;
            $rr_header->user = 'KCP';
            $rr_header->save();

            $rrcode = $rr_header->stockreceive_code;

            foreach($rrdetails as $rrdetail => $value){

                $rr_details = new StockreceiveDetails;

                $rr_details->stockreceive_code = $rrcode;
                $rr_details->stockcode = $value["stockcode"];
                $rr_details->unit = $value["unit"];
                $rr_details->qty = $value["qty"];
                $rr_details->cost = str_replace(",","",$value["cost"]);
                $rr_details->save();
            }

            if($this->IsDeliveryComplete($pocode) == true){
                
                $poheader = PurchaseorderHeader::find($pocode);

                $poheader->stockreceive_code = $rrcode;
                $poheader->save();
            }

            DB::commit();

            return response()->json(["message"=>"success"]);
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(["message"=>"failed"]);
        }
    }
}

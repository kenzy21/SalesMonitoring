<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CashTransactionHeader;
use App\CashTransactionDetails;
use App\Http\Traits\MasterfileTraits;
use App\Masterfile;
use App\Transaction;
use carbon\carbon;

class CashController extends Controller
{
    use MasterfileTraits;

    public function cash(){

        $discounts = DB::table("salesmonitoring.discount")
                     ->select(DB::raw("CONCAT(discount_type,'-',discount_desc) discount_"))
                     ->get();
        return view('Pages.CashTransaction',compact('discounts'));
    }

    public function savecashtransaction(Request $request){
        $clientid = $request->clientcode;
        $transactionlists = json_decode($request->transactionlist,true);
        $discounttype = $request->discounttype;
        $grossamount = $request->grossamount;
        $discount = $request->discount;
        $netamount = $request->netamount;
        $transdate = carbon::now();
        $transdate_prefix = $this->ConvertDateforPrefix($transdate);

        DB::beginTransaction();

        try{

            $cash_header = new CashTransactionHeader;    
            $cash_header->client_id = $clientid;
            $cash_header->transdate = $transdate;
            $cash_header->discount_type = $discounttype;
            $cash_header->amount = $grossamount;
            $cash_header->discount = $discount;
            $cash_header->net_amount = $netamount;
            $cash_header->user = "KCP";
            $cash_header->save();
    
            $transaction_code = $cash_header->id;
    
            $cash_header_update = CashTransactionHeader::find($transaction_code);
            $cash_header_update->transaction_code = $transdate_prefix . "-" . $transaction_code;
            $cash_header_update->save();
    
            foreach($transactionlists as $transactionlist => $value){
                // Get Stock balance
                $stockbal = $this->GetStockBalance($value["stockcode"]);

                $cash_details = new CashTransactionDetails;
                $cash_details->transaction_code = $transdate_prefix . "-" . $transaction_code;
                $cash_details->stockcode = $value["stockcode"];
                $cash_details->unit = $value["unit"];
                $cash_details->qty = $value["qty"];
                $cash_details->price = $value["price"];
                $cash_details->save();

                //Update qty in masterfile
                $masterfile = Masterfile::find($value["stockcode"]);
                $masterfile->qty = floatval($stockbal) - floatval($value["qty"]);
                $masterfile->save();

                //Insert to Transaction Table
                $transaction = new Transaction;
                $transaction->refid = $clientid;
                $transaction->referenceno = $transdate_prefix . "-" . $transaction_code;
                $transaction->stockcode = $value["stockcode"];
                $transaction->stockbal = $stockbal;
                $transaction->qty = $value["qty"];
                $transaction->cost = $value["price"];
                $transaction->amount = floatval($value["qty"]) * floatval($value["price"]);
                $transaction->runqty = floatval($stockbal) - floatval($value["qty"]);
                $transaction->trantype = "CA";
                $transaction->save();
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

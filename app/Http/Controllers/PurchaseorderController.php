<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Masterfile;
use App\Supplier;
use App\PurchaseorderHeader;
use App\PurchaseorderDetails;
use carbon\carbon;
use App\Http\Traits\MasterfileTraits;
use Illuminate\Support\Facades\DB;

class PurchaseorderController extends Controller
{

    use MasterfileTraits;

    public function purchaseorder(){
        $purchaseorders = DB::table('salesmonitoring.purchaseorder_header')
        ->select('terms','remarks','purchaseorder_code',
            DB::raw("CONCAT(purchaseorder_prefix,'-',purchaseorder_code) pono"),
            DB::raw("DATE_FORMAT(purchaseorder_date,'%m/%d/%Y') podate"),
            DB::raw("(SELECT FORMAT(SUM(cost * qty),2) FROM purchaseorder_details WHERE purchaseorder_details.purchaseorder_code = purchaseorder_header.purchaseorder_code) amount"),
            DB::raw("(SELECT supplier_name FROM salesmonitoring.supplier WHERE suppcode = purchaseorder_header.suppcode) supplier"))
        ->orderBY("purchaseorder_date","DESC")
        ->get();

        return view('Pages.PurchaseOrder',compact('purchaseorders'));
    }

    public function purchaseordercreate(){
        return view('Pages.PurchaseOrderCreate');
    }

    public function purchaseorderadditem(Request $request){
        $description = $request->description;

        $masterfile = Masterfile::find('stockcode',$description)->first('cost');

        return response()->json(["message"=>"success","cost"=>$masterfile->cost]);
    }

    public function purchaseordermasterfile(){
        $stockdesc = Masterfile::all('stockdesc','stockcode','currcost','unit');  

        return response()->json(["masterfile"=>$stockdesc]);
    }

    public function purchaseordersupplier(){
        $supplier = Supplier::all('suppcode','supplier_name','supplier_address','terms');
        
        return response()->json(["supplier"=>$supplier]);
    }

    public function purchaseordersavetransaction(Request $request){
        $supplier_code = $request->supplier_code;
        $supplier_terms = $request->supplier_terms;
        $po_remarks = strtoupper($request->po_remarks);
        $podetails = json_decode($request->purchaseorder_details,true);
        $po_date = Carbon::now();

        try{

            $po_header = new PurchaseorderHeader;

            $po_header->suppcode = $supplier_code;
            $po_header->purchaseorder_prefix = $this->ConvertDateforPrefix($po_date);
            $po_header->purchaseorder_date = $po_date;
            $po_header->terms = $supplier_terms;
            $po_header->remarks = $po_remarks;
            $po_header->user = 'KCP';
            $po_header->save();
            
            $pocode = $po_header->purchaseorder_code;

            foreach($podetails as $podetail => $value){
                    $po_details = new PurchaseorderDetails;
                    
                    $po_details->purchaseorder_code = $pocode;
                    $po_details->stockcode = $value["stockcode"];
                    $po_details->qty = $value["qty"];
                    $po_details->cost = str_replace(",","",$value["cost"]);
                    $po_details->unit = $value["unit"];
                    $po_details->save();
            }
     
            return response()->json(["message"=>"success"]);

        }
        catch(Exception $e){
            return response()->json(["message"=>"failed"]);
        }
    }

    public function purchaseorderdetails(Request $request){
        $pocode = $request->pocode;

        $podetails = DB::table("salesmonitoring.purchaseorder_details")
        ->select('qty','unit',
            DB::raw("(SELECT stockdesc FROM masterfile WHERE stockcode = purchaseorder_details.stockcode) stockdesc"),
            DB::raw("FORMAT(cost,2) cost"),
            DB::raw("FORMAT((qty * cost),2) amount"))
        ->where("purchaseorder_code",$pocode)
        ->orderBy("stockdesc")
        ->get();

        return response()->json(["message"=>json_encode($podetails)]);
    }
}

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
  
        $purchaseorders = $this->PurchaseorderHeader("","","poall");

        return view('Pages.PurchaseOrder',compact('purchaseorders'));
    }

    public function purchaseorderperiod(Request $request){
        $dtfrom = $request->dtfrom;
        $dtto = $request->dtto;
        $potype = $request->potype;

        $purchaseorders = $this->PurchaseorderHeader($dtfrom,$dtto,$potype);
 
        if(count($purchaseorders)==0){
            return response()->json(["message"=>"nodata"]);
        }

        return response()->json(["message"=>"success","purchaseorder"=>json_encode($purchaseorders)]);
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

    public function purchaseorderlist(){
        $polist = DB::table("salesmonitoring.purchaseorder_header")
            ->select('terms','purchaseorder_code','suppcode',
                DB::raw("DATE_FORMAT(purchaseorder_date,'%m/%d/%Y') podate"),
                DB::raw("(SELECT supplier_name FROM salesmonitoring.supplier WHERE suppcode = purchaseorder_header.suppcode) supplier"),
                DB::raw("(SELECT supplier_address FROM salesmonitoring.supplier WHERE suppcode = purchaseorder_header.suppcode) address"),
                DB::raw("CONCAT(purchaseorder_prefix,'-',purchaseorder_code) pono"))
            ->where("posted","Y")
            ->where("cancelled","N")
            ->whereNull("stockreceive_code")
            ->orderBy("purchaseorder_date")
            ->get();

        return response()->json(["polist"=>$polist]);
    }

    public function purchaseordersavetransaction(Request $request){
        $supplier_code = $request->supplier_code;
        $supplier_terms = $request->supplier_terms;
        $po_remarks = strtoupper($request->po_remarks);
        $podetails = json_decode($request->purchaseorder_details,true);
        $po_date = Carbon::now();

        try{

            DB::beginTransaction();

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
     
            DB::commit();

            return response()->json(["message"=>"success"]);

        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(["message"=>"failed"]);
        }
    }

    public function purchaseorderdetails(Request $request){
        
        $pocode = $request->pocode;
        $querytype = $request->querytype;

        $podetails = $this->GetPurchaseorderDetails($pocode,$querytype);

        if(count($podetails)==0){
            if($this->IsDeliveryComplete($pocode)){
                return response()->json(["message"=>"delivered"]);
            }
            return response()->json(["message"=>"nodata"]);
        }

        $poamount = array_sum($this->ConvertCollectionToArray($podetails,"amount_"));

        $rramount = array_sum($this->ConvertCollectionToArray($podetails,"rramount_"));

        return response()->json(["message"=>"success","podetails"=>json_encode($podetails),"poamount"=>$poamount,"rramount"=>$rramount]);
    }

    public function postpo(Request $request){
        $pono = $request->pono;
        $posteddate = Carbon::now();

        $purchaseorder = PurchaseorderHeader::find($pono);

        $purchaseorder->posted = "Y";
        $purchaseorder->posted_by = "KCP";
        $purchaseorder->posted_date = $posteddate;
        $purchaseorder->save();

        return response()->json(["message"=>"success"]);
    }

    public function cancelpo(Request $request){
        $pono = $request->pono;
        $canceldate = Carbon::now();

        $purchaseorder = PurchaseorderHeader::find($pono);

        $purchaseorder->cancelled = 'Y';
        $purchaseorder->cancelled_by = "KCP";
        $purchaseorder->cancelled_date = $canceldate;
        $purchaseorder->save();

        return response()->json(["message"=>"success"]);
    }
}

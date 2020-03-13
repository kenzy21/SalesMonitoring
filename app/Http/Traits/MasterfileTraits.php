<?php 

namespace App\Http\Traits;

use App\Classification;
use App\Generic;
use App\Stocktag;
use carbon\carbon;
use App\PurchaseorderHeader;
use App\Masterfile;
use Illuminate\Support\Facades\DB;

trait MasterfileTraits {
    
    // General functions
    public function ConvertCollectionToArray($collections,$field){
        $array_output = array();

        foreach($collections as $collection){
            array_push($array_output,$collection->$field);
        }            
        return $array_output;
    }

    // Masterfile functions
    public function GetClassificationCode($classdesc){
        $classificationcode = Classification::where('classificationdesc',$classdesc)->first('classcode');
        return $classificationcode->classcode;
    }

    public function GetGenericCode($gendesc){
     $genericcode = Generic::where('genericdesc',$gendesc)->first('gencode');
     return $genericcode->gencode;   
    }

    public function GetStocktag($stock){
        $stocktag = Stocktag::where('description',$stock)->first('stocktag');
        return $stocktag->stocktag;
    }

    public function GetStocktagdesc($stock){
        $stocktag = Stocktag::where('stocktag',$stock)-first('description');
        return $stocktag->description;
    }

    public function ConvertDateforPrefix($date){
        $dateconverted = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Ymd');
        return $dateconverted;
    }

    public function ConvertDate($date){
        $dateconverted = Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
        return $dateconverted;
    }

    public function CheckSerialized($stockcode){
        $stocktag = Masterfile::where('stockcode',$stockcode)
                ->where('serialize','Y')
                ->count();
                
        if($stocktag==1){
            return true;
        }
        return false;
    }

    // Stock Receive Functions
    public function IsDeliveryComplete($pocode){
        $delivery = DB::table("salesmonitoring.purchaseorder_header")
                    ->leftJoin("salesmonitoring.purchaseorder_details","purchaseorder_header.purchaseorder_code",
                                "=","purchaseorder_details.purchaseorder_code")
                    ->select("purchaseorder_header.purchaseorder_code","purchaseorder_details.stockcode",
                            DB::raw("(SUM(purchaseorder_details.qty) -
                                    IF((SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                    LEFT JOIN salesmonitoring.stockreceive_details
                                    ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                    WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code 
                                    AND stockreceive_details.stockcode = purchaseorder_details.stockcode) IS NULL , 0 ,
                                    (SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                    LEFT JOIN salesmonitoring.stockreceive_details
                                    ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                    WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code
                                    AND stockreceive_details.stockcode = purchaseorder_details.stockcode))) qty"))
                    ->where("purchaseorder_header.purchaseorder_code","$pocode")
                    ->groupBy("purchaseorder_details.stockcode")
                    ->havingRaw("qty > 0")
                    ->get();

        if(count($delivery) > 0){
            return false;
        }
        return true;
    }

    public function GetStockReceiveHeader($dtfrom,$dtto,$rrtype){

        if($rrtype == "rrperiod"){
            $dtfrom = $this->ConvertDate($dtfrom);
            $dtto = $this->ConvertDate($dtto);
        }

        $rrheader = DB::table("salesmonitoring.stockreceive_header")
                    ->select("stockreceive_code","remarks",
                        DB::raw("CONCAT(stockreceive_prefix,'-',stockreceive_code) rrno"),
                        DB::raw("DATE_FORMAT(stockreceive_date,'%m/%d/%Y') rrdate"),
                        DB::raw("DATE_FORMAT(stockreceive_date,'%Y-%m') rrdate_"),
                        DB::raw("(SELECT CONCAT(purchaseorder_prefix,'-',purchaseorder_code) FROM salesmonitoring.purchaseorder_header
                                    WHERE purchaseorder_header.purchaseorder_code = stockreceive_header.purchaseorder_code) pono"),
                        DB::raw("(SELECT supplier_name FROM salesmonitoring.supplier WHERE suppcode = (SELECT suppcode FROM salesmonitoring.purchaseorder_header
                                    WHERE purchaseorder_header.purchaseorder_code = stockreceive_header.purchaseorder_code)) supplier"),
                        DB::raw("FORMAT(gross_amount,2) grossamount"),
                        DB::raw("FORMAT(discount_amount,2) discountamount"),
                        DB::raw("FORMAT(net_amount,2) netamount"))
                    ->where(function($query) use($dtfrom,$dtto,$rrtype){
                        if($rrtype=="rrperiod")
                            $query->whereRaw("DATE_FORMAT(stockreceive_date,'%Y-%m-%d') BETWEEN '$dtfrom' AND '$dtto'");
                        if($rrtype=="rrall")
                            $query->HavingRaw("rrdate_ = DATE_FORMAT(NOW(),'%Y-%m')");
                    })
                    ->orderBy("stockreceive_date","DESC")
                    ->get();
                    
        return $rrheader;
    }

    public function GetStockReceiveDetails($rrcode){

        $rrdetails = DB::table("salesmonitoring.stockreceive_header")
                        ->leftJoin("salesmonitoring.stockreceive_details","stockreceive_header.stockreceive_code",
                                        "=","stockreceive_details.stockreceive_code")
                        ->select("stockreceive_details.stockcode","stockreceive_details.unit","stockreceive_details.qty",
                            DB::raw("CONCAT(stockreceive_header.stockreceive_prefix,'-',stockreceive_header.stockreceive_code) rrcode"),
                            DB::raw("DATE_FORMAT(stockreceive_header.stockreceive_date,'%m/%d/%Y') rrdate"),
                            DB::raw("FORMAT(stockreceive_header.net_amount,2) netamount"),
                            DB::raw("(SELECT CONCAT(purchaseorder_prefix,'-',purchaseorder_code) FROM salesmonitoring.purchaseorder_header
                                        WHERE purchaseorder_header.purchaseorder_code = stockreceive_header.purchaseorder_code) pono"),
                            DB::raw("(SELECT purchaseorder_header.terms FROM salesmonitoring.purchaseorder_header
                                        WHERE purchaseorder_header.purchaseorder_code = stockreceive_header.purchaseorder_code) terms"),
                            DB::raw("(SELECT supplier_name FROM salesmonitoring.supplier WHERE suppcode = (SELECT suppcode FROM salesmonitoring.purchaseorder_header
                                        WHERE purchaseorder_header.purchaseorder_code = stockreceive_header.purchaseorder_code)) supplier"),
                            DB::raw("FORMAT(stockreceive_header.net_amount,2) amount"),
                            DB::raw("(SELECT stockdesc FROM salesmonitoring.masterfile WHERE stockcode = stockreceive_details.stockcode) stockdesc"),
                            DB::raw("FORMAT(stockreceive_details.cost,2) cost"),
                            DB::raw("FORMAT((stockreceive_details.qty * stockreceive_details.cost),2) amount"))
                        ->where("stockreceive_header.stockreceive_code","$rrcode")
                        ->orderBy("stockdesc")
                        ->get();
                        
        return $rrdetails;
    }

    //Purchase Order Functions
    public function PurchaseorderHeader($dtfrom,$dtto,$potype){

        if($potype=="poperiod"){
            $dtfrom = $this->ConvertDate($dtfrom);
            $dtto = $this->ConvertDate($dtto);
        }

        $purchaseordersheader = DB::table('salesmonitoring.purchaseorder_header')
                        ->select('terms','remarks','purchaseorder_code','purchaseorder_date',
                            DB::raw("CONCAT(purchaseorder_prefix,'-',purchaseorder_code) pono"),
                            DB::raw("DATE_FORMAT(purchaseorder_date,'%m/%d/%Y') podate"),
                            DB::raw("DATE_FORMAT(purchaseorder_date,'%Y-%m') podate_"),
                            DB::raw("(SELECT FORMAT(SUM(cost * qty),2) FROM purchaseorder_details 
                                        WHERE purchaseorder_details.purchaseorder_code = purchaseorder_header.purchaseorder_code) amount"),
                            DB::raw("(SELECT supplier_name FROM salesmonitoring.supplier WHERE suppcode = purchaseorder_header.suppcode) supplier"),
                            DB::raw("CASE 
                                        WHEN purchaseorder_header.stockreceive_code IS NOT NULL 
                                            THEN 'D'
                                        WHEN (SELECT COUNT(stockreceive_header.stockreceive_code) FROM stockreceive_header 
                                                LEFT JOIN stockreceive_details 
                                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code) > 0
                                            THEN 'I'
                                        WHEN (SELECT COUNT(stockreceive_header.stockreceive_code) FROM stockreceive_header 
                                                LEFT JOIN stockreceive_details 
                                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code) = 0
                                            THEN 'P'                
                                    END deliverystatus"),
                            DB::raw("CASE   
                                        WHEN purchaseorder_header.cancelled = 'Y'
                                            THEN 'C'
                                        WHEN purchaseorder_header.posted = 'Y'
                                            THEN 'P'
                                        WHEN purchaseorder_header.posted = 'N'
                                            THEN 'U'
                                    END postatus"))
                        ->where(function($query) use($dtfrom,$dtto,$potype){
                            if($potype=="poall")
                                $query->havingRaw("podate_ > DATE_FORMAT(NOW(),'%Y-%m')");
                            if($potype=="poperiod")
                                $query->whereRaw("DATE_FORMAT(purchaseorder_date,'%Y-%m-%d') BETWEEN '$dtfrom' AND '$dtto'");
                        })
                        ->orderBY("purchaseorder_date","DESC")
                        ->get();
    
        return $purchaseordersheader;
    }

    public function GetPurchaseorderDetails($pocode,$trantype){

        $querycondition = "qty >= 0";

        if($trantype=="rr"){
            $querycondition = "qty > 0";
        }

        $podetails = DB::table("salesmonitoring.purchaseorder_header")
                    ->leftJoin("salesmonitoring.purchaseorder_details","purchaseorder_header.purchaseorder_code",
                                "=","purchaseorder_details.purchaseorder_code")
                    ->select("purchaseorder_header.purchaseorder_code","purchaseorder_details.stockcode",
                        DB::raw("(SELECT stockdesc FROM salesmonitoring.masterfile WHERE stockcode = purchaseorder_details.stockcode) stockdesc"),
                        DB::raw("SUM(purchaseorder_details.qty) poqty"),
                        DB::raw("FORMAT(purchaseorder_details.cost,2) cost"),
                        DB::raw("purchaseorder_details.unit unit"),
                        DB::raw("(SUM(purchaseorder_details.qty) * purchaseorder_details.cost) amount_"),
                        DB::raw("FORMAT((SUM(purchaseorder_details.qty) * purchaseorder_details.cost),2) amount"),
                        DB::raw("IF((SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode) IS NULL , 0 ,
                                (SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode)) rrqty"),
                        DB::raw("(SUM(purchaseorder_details.qty) -
                                IF((SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code 
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode) IS NULL , 0 ,
                                (SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode))) qty"),
                        DB::raw("FORMAT(((SUM(purchaseorder_details.qty) -
                                IF((SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code 
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode) IS NULL , 0 ,
                                (SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode))) * purchaseorder_details.cost),2) rramount"),
                        DB::raw("(((SUM(purchaseorder_details.qty) -
                                IF((SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code 
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode) IS NULL , 0 ,
                                (SELECT SUM(stockreceive_details.qty) FROM salesmonitoring.stockreceive_header
                                LEFT JOIN salesmonitoring.stockreceive_details
                                ON stockreceive_header.stockreceive_code = stockreceive_details.stockreceive_code
                                WHERE stockreceive_header.purchaseorder_code = purchaseorder_header.purchaseorder_code
                                AND stockreceive_details.stockcode = purchaseorder_details.stockcode))) * purchaseorder_details.cost)) rramount_"))
                    ->where("purchaseorder_header.purchaseorder_code","$pocode")
                    ->groupBy("purchaseorder_details.stockcode")
                    ->havingRaw(DB::raw("$querycondition"))
                    ->orderBy("stockdesc")
                    ->get();

        return $podetails;
    }
}
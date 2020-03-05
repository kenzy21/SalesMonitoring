<?php 

namespace App\Http\Traits;

use App\Classification;
use App\Generic;
use App\Stocktag;
use carbon\carbon;
use App\PurchaseorderHeader;

trait MasterfileTraits {
    
    public function ConvertCollectionToArray($collections,$field){
        $array_output = array();

        foreach($collections as $collection){
            array_push($array_output,$collection->$field);
        }            
        return $array_output;
    }

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
}
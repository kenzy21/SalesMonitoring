<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Stocktag;
use App\Classification;
use App\Rules\ClassificationRule;
use App\Rules\GenericRule;
use App\Rules\DescriptionRule;
use App\Masterfile;
use App\Http\Traits\MasterfileTraits;
use Illuminate\Support\Facades\auth;
use App\Unitmast;

class MasterFileController extends Controller
{

    use MasterfileTraits;

    public function masterfile(){
    
          $stockmast = DB::table("salesmonitoring.masterfile")
                ->select('stockcode','barcode','reorder','active','unit','serialize',
                DB::raw("UCASE(stockdesc) stockdesc"),
                DB::raw("(SELECT UCASE(classificationdesc) FROM salesmonitoring.classification WHERE classcode = masterfile.classcode) classification"),
                DB::raw("(SELECT UCASE(genericdesc) FROM salesmonitoring.generic WHERE gencode = masterfile.gencode) generic"),
                DB::raw("(SELECT description FROM salesmonitoring.stocktag WHERE stocktag = masterfile.stocktag) stocktag"),
                DB::raw("FORMAT(currcost,2) cost"))
        ->orderBy("stockdesc")
        ->get();

        $stocktag = Stocktag::all('description');

        $unitmast = Unitmast::all('unit');

        return view('Pages.MasterFile',compact('stockmast','stocktag','unitmast'));
    }

    public function masterfileclassification(){ 
        $classification = DB::table("salesmonitoring.classification")->get('classificationdesc');

        $class_converted = $this->ConvertCollectionToArray($classification,"classificationdesc");

        return response()->json(['classification'=>$class_converted]);
    }

    public function masterfilegeneric(){
        $generic = DB::table("salesmonitoring.generic")->get('genericdesc');

        $generic_converted = $this->ConvertCollectionToArray($generic,"genericdesc");

        return response()->json(['generic'=>$generic_converted]);
    }

    public function masterfilecreate(Request $request){
        $barcode = $request->barcode;
        $description = strtoupper($request->description);
        $classification = $request->classification;
        $generic = $request->generic;
        $unit = $request->unit;
        $stocktag = $request->stocktag; 
        $reorder = $request->reorder;
        $cost = str_replace(",","",$request->cost);
        $active = $request->active;
        $serialize = $request->serialize;

        $validation = Validator::make($request->all(),[
            'description' => 'required|unique:masterfile,stockdesc',
            'classification' => ['required',new ClassificationRule],
            'generic' => ['required',new GenericRule]
        ],
        [
            'description.required' => 'Stock description is required.',
            'description.unique' => 'Stock description is already exist.',
            'classification.required' => "Classification is required.",
            'generic.required' => 'Generic is required.'
        ]);

        if($validation->fails()){
            return response()->json(['message'=>$validation->errors()->first()]);
        }

         $masterfile = new Masterfile;

         $masterfile->barcode = trim($barcode);
         $masterfile->stockdesc = $description;
         $masterfile->classcode = $this->GetClassificationCode($classification);
         $masterfile->gencode = $this->GetGenericCode($generic);
         $masterfile->unit = $unit;
         $masterfile->stocktag = $this->GetStocktag($stocktag);
         $masterfile->serialize = $serialize;
         $masterfile->reorder = $reorder;
         $masterfile->currcost = $cost;
         $masterfile->active = $active; 
         $masterfile->user = 'KCP';
         $masterfile->save();

        return response()->json(['message'=>'success']);
    }

    public function masterfileupdate(Request $request){
         $stockcode = $request->stockcode;
         $barcode = $request->barcode;
         $description = strtoupper($request->description);
         $classification = $request->classification;
         $generic = $request->generic;
         $unit = $request->unit;
         $stocktag = $request->stocktag;
         $reorder = $request->reorder;
         $cost = str_replace(",","",$request->cost);
         $active = $request->active;
         $serialize = $request->serialize;

        
        $validation = validator::make($request->all(),[
            'description' => ['required',new DescriptionRule($stockcode)],
            'classification' => ['required',new ClassificationRule],
            'generic' => ['required',new GenericRule]
        ],
        [
            'description.required' => 'Description is required.',
            'classification.required' => 'Classification is required.',
            'generic.required' => 'Generic is requried.'
        ]);

        if ($validation->fails()){
            return response()->json(["message"=>$validation->errors()->first()]);
        }
        
        $stockmasterfile = Masterfile::find($stockcode);

        $stockmasterfile->barcode = strtoupper($barcode);
        $stockmasterfile->stockdesc = strtoupper($description);
        $stockmasterfile->classcode = $this->GetClassificationCode($classification);
        $stockmasterfile->gencode = $this->GetGenericCode($generic);
        $stockmasterfile->unit = $unit;
        $stockmasterfile->stocktag = $this->GetStocktag($stocktag);
        $stockmasterfile->serialize = $serialize;
        $stockmasterfile->reorder = $reorder;
        $stockmasterfile->currcost = $cost;
        $stockmasterfile->active = $active;
        $stockmasterfile->save();

        return response()->json(["message"=>"success"]);
    }
}

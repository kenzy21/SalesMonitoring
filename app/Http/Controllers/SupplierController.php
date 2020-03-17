<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use App\Rules\SupplierRule;

class SupplierController extends Controller
{
    public function supplier(){
        $suppliers = Supplier::all();
        return view('Pages.Supplier',compact('suppliers'));
    }

    public function suppliercreate(Request $request){
        $supplier_name = strtoupper($request->supplier_name);
        $supplier_name_short = strtoupper($request->supplier_name_short);
        $supplier_address = strtoupper($request->supplier_address);
        $supplier_terms = $request->supplier_terms;

        $validation = Validator::make($request->all(),[
            'supplier_name' => 'required|unique:supplier,supplier_name',
            'supplier_address' => 'required',
            'supplier_terms' => 'required'
        ],
        [
            'supplier_name.required' => 'Supplier name is required.',
            'supplier_name.unique' => 'Supplier name is already exist.',
            'supplier_address' => 'Supplier address is required.',
            'supplier_terms' => 'Please input terms base on days.'
        ]);

        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()->first()]);
        }

        $supplier = new Supplier;

        $supplier->supplier_name = $supplier_name;
        $supplier->supplier_short_name = $supplier_name_short;
        $supplier->supplier_address = $supplier_address;
        $supplier->terms = $request->supplier_terms;
        $supplier->user = 'KCP';
        $supplier->save();

        return response()->json(["message"=>"success"]);
    }

    public function supplieredit(Request $request){
        $supplier_code = $request->supplier_code;
        $supplier_name = strtoupper($request->supplier_name);
        $supplier_short_name = strtoupper($request->supplier_short_name);
        $supplier_address = strtoupper($request->supplier_address);
        $supplier_terms = $request->supplier_terms;

        $validation = Validator::make($request->all(),[
            'supplier_name' => ['required',new SupplierRule($supplier_code)]
        ]);

        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()->first()]);
        }

        $supplier = Supplier::find($supplier_code);

        $supplier->supplier_name = $supplier_name;
        $supplier->supplier_short_name = $supplier_short_name;
        $supplier->supplier_address = $supplier_address;
        $supplier->terms = $supplier_terms;
        $supplier->save();

        return response()->json(["message"=>"success"]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Client;
use App\Rules\ClientRule;

class ClientController extends Controller
{
    public function client(){
        $clients = Client::all();
        return view('Pages.Client',compact('clients'));
    }

    public function clientcreate(Request $request){
        $client_name  = strtoupper($request->client_name);
        $client_address = strtoupper($request->client_address);

        $validation = Validator::make($request->all(),[
            'client_name' => 'required|unique:clients,client_name'
        ],
        [
            'client_name.required' => 'Client name is requried.',
            'client_name.unique' => 'Client name is already exists.'
        ]);

        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()->first()]);
        }

        $client = new Client;
        $client->client_name = $client_name;
        $client->client_address = $client_address;
        $client->user = "KCP";
        $client->save();

        return response()->json(["message"=>"success"]);
    }

    public function clientedit(Request $request){
        $client_code = $request->client_code;
        $client_name = strtoupper($request->client_name);
        $client_address = strtoupper($request->client_address);

        $validation = Validator::make($request->all(),[
            'client_name' => ['required',new ClientRule($client_code)]
        ],
        [
            'client_name.required' => 'Client name is required.'
        ]);

        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()->first()]);
        }

        $client = Client::find($client_code);

        $client->client_name = $client_name;
        $client->client_address = $client_address;
        $client->save();
        
        return response()->json(["message"=>"success"]);
    }
}

<?php

//Masterfile
Route::get('/masterfile','MasterFileController@masterfile');
Route::get('/masterfile/classification','MasterFileController@masterfileclassification');
Route::get('/masterfile/generic','MasterFileController@masterfilegeneric');
Route::post('/masterfile/create','MasterFileController@masterfilecreate');
Route::post('/masterfile/update','MasterFileController@masterfileupdate');


//Supplier
Route::get('/supplier','SupplierController@supplier');
Route::post('/supplier/create','SupplierController@suppliercreate');
Route::post('/supplier/edit','SupplierController@supplieredit');

//Client
Route::get('/client','ClientController@client');
Route::post('/client/create','ClientController@clientcreate');
Route::post('/client/edit','ClientController@clientedit');

//Purchase Order
Route::get('/purchaseorder','PurchaseorderController@purchaseorder');
Route::get('/purchaseorder/create','PurchaseorderController@purchaseordercreate');
Route::get('/purchaseorder/additem','PurchaseorderController@purchaseorderadditem');
Route::get('/purchaseorder/masterfile','PurchaseorderController@purchaseordermasterfile');
Route::get('/purchaseorder/supplier','PurchaseorderController@purchaseordersupplier');
Route::post('/purchaseorder/save/transaction','PurchaseorderController@purchaseordersavetransaction');
Route::get('/purchaseorder/details','PurchaseorderController@purchaseorderdetails');
Route::get('/purchaseorder/period','PurchaseorderController@purchaseorderperiod');
Route::get('/purchaseorder/list','PurchaseorderController@purchaseorderlist');

//Stock Receive
Route::get('/stockreceive','StockReceiveController@stockreceive');
Route::get('/stockreceive/create','StockReceiveController@stockreceivecreate');

//For Test
Route::get('/test','TestController@test');
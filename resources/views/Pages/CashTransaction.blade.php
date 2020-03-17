@extends('layouts.Master')

@section('title','Cash Transaction')

@section('content')
    <div class="col-xl-8 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cash Transaction</h6>
            </div>
            <div class="card-body">   
                <form id="purchaseorder-create">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Client</label>
                            <input id="client" type="text " class="form-control form-control-sm" style="text-transform:uppercase;" placeholder = "Supplier">
                        </div>
                        <div class="form-gorup col-md-4">
                            <label for="">Transaction Date</label>
                            <input type="text" id="trandate" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Transaction Type</label>
                            <input type="text" class="form-control form-control-sm" value="CASH" readonly>
                        </div>
                    </div>
                </form>   
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <button id="additem" class="btn btn-primary btn-block">Add Item</button>
                        </div>
                        <div class="form-group col-md-9">
                            <input type="text" class="form-control" id="barcode" style="text-transform:uppercase;"placeholder="BARCODE">
                        </div>
                    </div>
                    <hr>
                    <div style="overflow-x:auto;"> 
                        <table class="table table-bordered" id="trans-list">
                            <thead class="thead-light">
                                <th style="width:30px;">#</th>
                                <th style="width:30px;" hidden>Stockcode</th>
                                <th style="width:340px;">Stock Description</th>
                                <th style="width:30px;">Unit</th>
                                <th style="width:60px;" class="text-right">Qty</th>
                                <th style="width:90px;" class="text-right"> Cost</th>
                                <th style="width:120px;" class="text-right">Total</th>
                                <th style="width:30px;" class="text-center">Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Gross :</span></div>
                        <div class="col-md-2"><input type="text" id="total-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Discount :</span></div>
                        <div class="col-md-2"><input type="text" id="total-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Payment :</span></div>
                        <div class="col-md-2"><input type="text" id="total-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Net :</span></div>
                        <div class="col-md-2"><input type="text" id="total-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Change :</span></div>
                        <div class="col-md-2"><input type="text" id="total-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-10">
                                <span style="font-size:75%">F1 - Add Item | F2 - Save Transaction | F3 - Discount | F4 - Payment</span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" id="save-transaction" style="float:right; margin-top:10px">Save Transaction</button>
                            </div>
                        </div>
                    </div>         
             </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.getScript('/js/GetCurrentDate.js', function() {
                 $("#trandate").val(GetTodayDate());
            });

            $("#trans-list").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });

        });
    </script>
@endsection
@extends('layouts.Master')

@section('title','Cash Transaction')

@section('content')
    <div class="col-xl-8 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Cash Transaction</h6>
            </div>
            <div class="card-body">   
                <form id="cashtransaction-create">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Client</label>
                            <input id="client" type="text " class="form-control form-control-sm" style="text-transform:uppercase;">
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
                        <div class="col-md-2"><input type="text" id="gross-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Discount :</span></div>
                        <div class="col-md-2"><input type="text" id="discount-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Net :</span></div>
                        <div class="col-md-2"><input type="text" id="net-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Payment :</span></div>
                        <div class="col-md-2"><input type="text" id="payment-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Change :</span></div>
                        <div class="col-md-2"><input type="text" id="change-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-10">
                                <span style="font-size:75%">F1 - Add Item | F2 - Save Transaction | F4 - Payment | F8 - Discount</span>
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
    @include('Pages.Modal.CashTransactionAddItem')
    @include('Pages.Modal.Discount')
    <script>
        $(document).ready(function(){
            var clientcode;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            document.onkeyup = KeyCheck;
            
            document.getElementById("cashtransaction-create").reset(); 
            $("#gross-amount").val("0.00");
            $("#discount-amount").val("0.00");
            $("#payment-amount").val("0.00");
            $("#net-amount").val("0.00");
            $("#change-amount").val("0.00");

            function KeyCheck(e){
                var KeyID = (window.event) ? event.keyCode : e.keyCode;

                if(KeyID == 112){
                    $("#additem").trigger("click");
                }
                else if(KeyID == 113){
                    //$("#save-transaction").trigger("click");
                }
                else if(KeyID == 119){
                   var dueamount = $("#gross-amount").val();
                   $("#dueamount").val(dueamount);
                   $("#cashtransaction-discount").modal("show");
                }
                else if(KeyID == 115){
                    $("#payment-amount").select();
                }
            };

            $.getScript('/js/GetCurrentDate.js', function() {
                 $("#trandate").val(GetTodayDate());
            });

            $("#trans-list").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });

            $.ajax({
                type: "GET",
                url: "/reference/client/list",
                success:function(result){
                    var option = {
                        data:result.clients,
                        getValue: "client_name",
                        placeholder: "Client",
                        adjustWidth: false,
                        list:{
                            match:{
                                enabled: true
                            },
                            onChooseEvent:function(){
                                clientcode = $("#client").getSelectedItemData().client_code;
                            }
                        }
                    }
                    $("#client").easyAutocomplete(option);
                }
            });

            $("#additem").click(function(){
                $("#cashtransaction-additem").modal("show");
            });

            $("#payment-amount").on("keyup",function(){
                var total;
                var grossamount = $("#gross-amount").val();
                var payment = $("#payment-amount").val();
                var discount = $("#discount-amount").val();

                total = parseFloat(grossamount.replace(",","")) - (parseFloat(payment.replace(",","")) + parseFloat(discount.replace(",","")));

                if(payment == ""){
                    $("#payment-amount").val("0.00");
                    total = "0.00";
                    $("#payment-amount").select();
                }         

                $("payment-amount")
                $("#change-amount").val(accounting.formatMoney(total, { symbol: "",  format: "%v %s" }));
            });

            $("#save-transaction").on("click",function(){

            });
        
        });
    </script>
@endsection
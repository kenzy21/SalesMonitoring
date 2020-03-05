@extends('layouts.Master')

@section('title','Create Purchase Order')

@section('content')
    <div class="col-xl-8 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Create Purchase Order</h6>
            </div>
            <div class="card-body">   
                <form id="purchaseorder-create">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Supplier</label>
                            <input id="supplier" type="text " class="form-control form-control-sm" style="text-transform:uppercase;" placeholder = "Supplier">
                        </div>
                        <div class="form-gorup col-md-4">
                            <label for="">Purchase Order Date</label>
                            <input type="text" id="podate" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Terms</label>
                            <input type="text" id="terms" class="form-control form-control-sm" style="text-transform:uppercase;" placeholder ="Number of days.">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        <input type="text" id="address" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Remarks</label>
                        <textarea type="text" class="form-control" id="remarks" style="resize:none; text-transform:uppercase;"></textarea>
                    </div>
                </form>   
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <button id="additem" class="btn btn-primary btn-block">Add Item</button>
                        </div>
                        <div class="form-group col-md-9"></div>
                    </div>
                    <hr>
                    <div style="overflow-x:auto;"> 
                        <table class="table table-bordered" id="list-items">
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
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Total :</span></div>
                        <div class="col-md-2"><input type="text" id="total-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-10">
                                <span style="font-size:80%">F1 - Add Item | F2 - Save Transaction</span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" id="save-transaction" style="float:right; margin-top:10px">Save Transaction</button>
                            </div>
                    </div>         
             </div>
        </div>
    </div>
    @include('Pages.Modal.PurchaseOrderAddItem')
    @include('Pages.Modal.PurchaseOrderEditItem')
    <script>
        $(document).ready(function(){       
            var suppliercode,suppliername,supplieraddress,supplierterms;

            document.onkeyup = KeyCheck;

            $("#total-amount").val("0.00");

            function KeyCheck(e){
                var KeyID = (window.event) ? event.keyCode : e.keyCode;

                if(KeyID == 112){
                    $("#purchaseorder-additem").modal("show");
                }
                else if(KeyID == 113){
                    $("#save-transaction").trigger("click");
                }
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            setTimeout(function(){
                $("#supplier").select();
            },500);

            $.getScript('/js/GetCurrentDate.js', function() {
                 $("#podate").val(GetTodayDate());
            });
            
            $("#list-items").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });

            $.ajax({
                type: "GET",
                url: "/purchaseorder/supplier",
                success:function(result){
                    var option = {
                        data:result.supplier,
                        getValue: "supplier_name",
                        placeholder: "Supplier",
                        adjustWidth: false,
                        list:{
                            match:{
                                enabled: true
                            },
                            onChooseEvent:function(){
                                suppliercode = $("#supplier").getSelectedItemData().suppcode;
                                suppliername = $("#supplier").getSelectedItemData().supplier_name;
                                supplieraddress = $("#supplier").getSelectedItemData().supplier_address;
                                supplierterms = $("#supplier").getSelectedItemData().terms;

                                $("#address").val(supplieraddress);
                                $("#terms").val(supplierterms);
                            }
                        }
                    }
                    $("#supplier").easyAutocomplete(option);
                }
            });

            $("#additem").on("click",function(){
                $("#purchaseorder-additem").modal("show");          
            });

            function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode != 46 && charCode > 31 
                    && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            };

            $("#quantity-additem").on("keypress",function(event){
                return isNumberKey(event);
            });

            $("#cost-additem").on("keypress",function(event){
                return isNumberKey(event);
            });

            $("#terms").on("keypress",function(event){
                return isNumberKey(event);
            });

            $("#quantity-edititem").on("keypress",function(event){
                return isNumberKey(event);
            });

            $("#list-items").on("click","tbody td #edit",function(){
                var CurrentRow = $(this).closest("tr");
                rowidx = CurrentRow.find("td:eq(0)").text();

                $("#rowno").val(rowidx);
                $("#description-edititem").val(CurrentRow.find("td:eq(2)").text());
                $("#unit-edititem").val(CurrentRow.find("td:eq(3)").text());
                $("#cost-edititem").val(CurrentRow.find("td:eq(5)").text().trim());
                $("#quantity-edititem").val(CurrentRow.find("td:eq(4)").text().trim());
                $("#purchaseorder-edititem").modal("show");
            });

            $("#save-transaction").on("click",function(){
                var terms = $("#terms").val();
                var tablerows = $("#list-items tbody tr").length;

                if(suppliercode == "" || suppliercode == undefined){
                        Swal.fire(
                            'Please check supplier.',
                            '',
                            'warning'
                        ).then(function(){
                            $("#supplier").focus();
                            e.preventDefault();
                        });
                }
                else if(terms == "" || parseFloat(terms) == 0){
                        Swal.fire(
                                'Please check terms.',
                                '',
                                'warning'
                        ).then(function(){
                            $("#terms").focus();
                            e.preventDefault();
                        });
                }
                else if(tablerows == 0){
                        Swal.fire(
                            "There's no data to be saved.",
                            '',
                            'error'
                        )
                }
                else{
                    var purchaseorder_details = [];
                    var supplier_code = suppliercode;
                    var supplier_terms = $("#terms").val();
                    var po_remarks = $("#remarks").val();
                    var po_date = $("#podate").val();
                    
                    $("#list-items tr").each(function(i){
                        if(i==0) return;
                        var stockcode = $.trim($(this).find("td").eq(1).html());
                        var unit = $.trim($(this).find("td").eq(3).html());
                        var qty = $.trim($(this).find("td").eq(4).html());
                        var cost = $.trim($(this).find("td").eq(5).html());
                        var amount = $.trim($(this).find("td").eq(6).html());

                        purchaseorder_details.push({stockcode: stockcode,unit: unit,qty: qty,cost: cost,amount: amount});
                    });

                    Swal.fire({
                                title: 'Are you sure you want to save it?',
                                text: "",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes'
                        }).then((result) => {
                            if(result.value){
                                $.ajax({
                                    type: "POST",
                                    url: "/purchaseorder/save/transaction",
                                    data: {
                                        supplier_code:supplier_code,
                                        po_date:po_date,
                                        supplier_terms:supplier_terms,
                                        po_remarks:po_remarks,
                                        purchaseorder_details:JSON.stringify(purchaseorder_details)
                                    },
                                    success:function(result){                         
                                            if(result.message == "success"){
                                                Swal.fire(
                                                    'Data successfully saved.',
                                                    '',
                                                    'success'
                                                ).then(function(){
                                                        $("#purchaseorder-create").trigger("reset");
                                                        $("#list-items tbody").empty();
                                                        $("#supplier").focus();
                                                        $("#total-amount").val("0.00");
                                                })
                                            }
                                            else{
                                                Swal.fire(
                                                    'Something went wrong.',
                                                    '',
                                                    'error'
                                                )
                                            }
                                        }
                                    });
                                }
                            })

                        }
                });

        });

    </script>
@endsection
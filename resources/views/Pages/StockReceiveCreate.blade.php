@extends('layouts.Master')

@section('title','Stock Receive')

@section('content')
<div class="col-xl-8 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Stock Receive (RR)</h6>
            </div>
            <div class="card-body">   
                <form id="stockreceive-create">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <input id="pono" type="text" class="form-control" placeholder="PO NO.">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Supplier</label>
                            <input id="supplier" type="text " class="form-control form-control-sm" style="text-transform:uppercase;" placeholder = "Supplier" readonly>
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
                                <th style="width:120px;" class="text-center">Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Gross Amount :</span></div>
                        <div class="form-group col-md-2"><input type="text" id="gross-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Discount :</span></div>
                        <div class="form-group col-md-2"><input type="text" id="discount-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 text-right" style="padding-top:10px;"><span>Net Amount :</span></div>
                        <div class="col-md-2"><input type="text" id="net-amount" class="form-control" style="text-align:right;font-size:1.3em;" value="0.00" readonly></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10 " style="padding-top:10px;">
                                <span style="font-size:80%;">F2 - Save Transaction</span>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" id="save-transaction" style="float:right; margin-top:10px">Save Transaction</button>
                            </div>
                        </div>     
                    </div>
                </div>
            </div>
    @include('Pages.Modal.StockreceiveEditItem');
    <script>
        $(document).ready(function(){
            var pocode,suppcode;

            setTimeout(function(){
                $("#pono").focus();
            },500);

            //Shortcut keys
            document.onkeyup = KeyCheck;

            function KeyCheck(e){
                var KeyID = (window.event) ? event.keyCode : e.keyCode;

                if(KeyID == 113){
                    $("#save-transaction").trigger("click");
                }
            };
            //End 

            $.ajax({
                type: "GET",
                url: "/purchaseorder/list",
                success:function(result){
                    var options = {
                        data:result.polist,
                        getValue: "pono",
                        adjustWidth: false,
                        list:{
                            match:{
                                enabled: true
                                },
                                onChooseEvent:function() {
                                    pocode = $("#pono").getSelectedItemData().purchaseorder_code;
                                    suppcode = $("#pono").getSelectedItemData().suppcode;
                                    $("#terms").val($("#pono").getSelectedItemData().terms);
                                    $("#supplier").val($("#pono").getSelectedItemData().supplier);
                                    $("#podate").val($("#pono").getSelectedItemData().podate)
                                    $("#address").val($("#pono").getSelectedItemData().address);
                                    $("#remarks").val($("#pono").getSelectedItemData().remarks);
        
                                    $.ajax({
                                        type: "GET",
                                        url: "/purchaseorder/details",
                                        data:{
                                            pocode:pocode
                                        },
                                        success:function(result){
                                            if(result.message=="success"){
                                                $("#list-items tbody tr").empty();
                                                var poamount,discountamount,netamount;
                                                poamount = accounting.formatMoney(result.poamount, { symbol: "",  format: "%v %s" });
                                                discountamount = $("#discount-amount").val();
                                                netamount = parseFloat(poamount.replace(",","")) + parseFloat(discountamount);
                                                $.each(JSON.parse(result.podetails),function(i,item){
                                                        ListPODetails(i+1,item.stockcode,item.stockdesc,item.unit,item.qty,item.cost,item.amount);
                                                })
                                                $("#gross-amount").val(poamount);
                                                $("#net-amount").val(accounting.formatMoney(netamount, { symbol: "",  format: "%v %s" }));
                                                $("#terms").select();
                                            }
                                            else if(result.message=="nodata"){
                                                Swal.fire(
                                                    "There's no data.",
                                                    'Please select the PO No. again.',
                                                    'warning'
                                                )
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
                                  }
                               };
                            $("#pono").easyAutocomplete(options);
                         }
                    });
            
            //Clear fields when refresh | discount input events
            document.getElementById('stockreceive-create').reset();

            function ClearFields(){
                $("#gross-amount").val("0.00");
                $("#discount-amount").val("0.00");
                $("#net-amount").val("0.00")
            };

            ClearFields();

            $("#discount-amount").on("focus",function(){
                $("#discount-amount").select();
            });

            $("#discount-amount").on("focusout",function(){
                var discount = $("#discount-amount").val();

                $("#discount-amount").val(accounting.formatMoney(discount, { symbol: "",  format: "%v %s" }))
            });
            
            function SetTotalAmountDiscount(){
                var gross = $("#gross-amount").val();
                var discount = $("#discount-amount").val();
                var net = parseFloat(gross.replace(",","")) - parseFloat(discount.replace(",",""));
                $("#net-amount").val(accounting.formatMoney(net, { symbol: "",  format: "%v %s" }));
            }

            function SetTotalAmount(){
                $.getScript('/js/GetTotalAmount.js',function(){
                    var totalamount = TotalAmount("#list-items tr",6);
                    $("#gross-amount").val(totalamount);
                    SetTotalAmountDiscount();
                });
            };

            function SetRow(){
                $('#list-items tbody tr').each(function(idx){
                    $(this).children(":eq(0)").html(idx + 1);
                });
                SetTotalAmount();
            };

            $("#discount-amount").on("keypress",function(event){
                return isNumberKey(event);
            });

            function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode != 46 && charCode > 31 
                    && (charCode < 48 || charCode > 57))
                    return false;
                    return true;
            };

            $("#discount-amount").on("keyup",function(){
                var discount = $("#discount-amount").val();
                if(discount == ""){
                    $("#discount-amount").val(accounting.formatMoney(0, { symbol: "",  format: "%v %s" }))
                    SetTotalAmountDiscount();
                    $("#discount-amount").select();
                }
                else{
                    SetTotalAmountDiscount();
                }
            });

            $("#list-items").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });
            //End

            function ListPODetails(row_,stockcode_,stockdesc_,unit_,qty_,cost_,amount_){
                var polist = "<tr>  \
                                <td>" + row_ + "</td> \
                                <td hidden>" + stockcode_ + "</td> \
                                <td>" + stockdesc_ + "</td> \
                                <td>" + unit_ + "</td> \
                                <td class='text-right'>" + qty_ + "</td> \
                                <td class='text-right'>" + cost_ + "</td> \
                                <td class='text-right'>" + amount_ + "</td> \
                                <td class='text-center'> \
                                    <a href='#' id='remove' title='Remove'><span><i class='fas fa-trash-alt' style='color:#427bf5;'></i></span><a/> \
                                    <span>|</span> \
                                    <a href='#' id='edit' title='Edit'><span><i class='fas fa-edit' style='color:#427bf5;'></i></span></a> \
                                    <span>|</span> \
                                    <a href='#' title='Serial'><i class='fas fa-barcode' style='color:#427bf5;'></i></a> \
                                </td> \
                            </tr>";
                $("#list-items tbody").append(polist);
            };

            $("#list-items").on("click","tbody td #remove",function(){
                Swal.fire({
                    title: "Are you sure you want to remove it?",
                    text: "",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                if(result.value){
                    if($("#list-items tbody tr").length == 1){
                        ClearFields();
                    }
                    $(this).closest("tr").remove();
                    SetRow();
                    }
                });
             });

             $("#list-items").on("click", "tbody td #edit",function(){
                    var CurrRow = $(this).closest("tr");
                    var rowno = CurrRow.find("td:eq(0)").text();
                    var stockdesc = CurrRow.find("td:eq(2)").text();
                    var unit = CurrRow.find("td:eq(3)").text();
                    var qty = CurrRow.find("td:eq(4)").text();
                    var cost = CurrRow.find("td:eq(5)").text();

                    $("#rowno").val(rowno);
                    $("#description-edititem").val(stockdesc);
                    $("#unit-edititem").val(unit);
                    $("#quantity-edititem").val(qty);
                    $("#cost-edititem").val(cost);
                    $("#stockreceive-edititem").modal("show");
             });

        });
    </script>
@endsection
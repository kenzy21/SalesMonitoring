@extends('layouts.Master')

@section('title','Purchase Order')

@section('content')
        <div class="col-xl-10 col-md-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Order</h6>
                </div>
                    <div class="card-body">                 
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <a href="{{ url('/purchaseorder/create') }}" class="btn btn-primary btn-block" style="margin-bottom:10px;">Create</a>
                            </div>
                            <div class="form-group col-md-9"></div>
                        </div>       
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <div class="InputWithIcon">
                                    <input class="form-control-custom" name="dtfrom" id="dtfrom" placeholder = "From" >
                                    <i class="fas fa-calendar-alt" style="color:gray;"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="InputWithIcon">
                                    <input class="form-control-custom" name="dtfrom" id="dtto" placeholder = "To" >
                                    <i class="fas fa-calendar-alt" style="color:gray;"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <button class="btn btn-primary btn-block" id="search">Search</button>
                            </div>
                            <div class="form-group col-md-1"></div>
                        </div>  
                        <input type="text" class="form-control" id="po-search"placeholder="SEARCH PO NO.">
                        <hr>
                        <div style="overflow-x:auto;">  
                            <table class="table" id="polist">
                                <thead class="thead-light">
                                    <th hidden>purchasecode</th>
                                    <th>PO No.</th>
                                    <th>PO Date</th>
                                    <th class="text-right">Terms</th>
                                    <th class="text-right">Amount</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th class="text-center">Details</th>
                                    <th class="text-center">Action</th>
                                </thead>
                                <tbody>
                                    @foreach($purchaseorders as $purchaseorder)
                                        <tr>
                                            <td hidden>{{ $purchaseorder->purchaseorder_code }}</td>
                                            <td>{{ $purchaseorder->pono }} </td>
                                            <td>{{ $purchaseorder->podate }}</td>
                                            <td class="text-right">{{ $purchaseorder->terms }} </td>
                                            <td class="text-right">{{ $purchaseorder->amount }}</td>
                                            <td>{{ $purchaseorder->supplier }}</td>
                                            @if($purchaseorder->postatus == "U")
                                                <td class="text-center"><div class="alert alert-secondary" style="font-size:60%;">Unposted</div></td>
                                            @elseif($purchaseorder->postatus == "P" && $purchaseorder->deliverystatus == "D")
                                                <td class="text-center"><div class="alert alert-success" style="font-size:60%;">Delivered</div></td>
                                            @elseif($purchaseorder->postatus == "P" && $purchaseorder->deliverystatus == "I")
                                                <td class="text-center"><div class="alert alert-warning" style="font-size:60%;">Incomplete</div></td>
                                            @elseif($purchaseorder->postatus == "P" && $purchaseorder->deliverystatus == "P")
                                                <td class="text-center"><div class="alert alert-primary" style="font-size:60%;">Posted</div></td>
                                            @elseif($purchaseorder->postatus ==  "C")
                                                <td class="text-center" ><div class="alert alert-danger" style="font-size:60%;">Cancelled</div></td>                                    
                                            @endif
                                            <td class="text-center"><a href="javascript:void()"><i class="fas fa-bars" style="color:#427bf5;"></i></a></td>
                                            <td colspan = "2" class="text-center">
                                                <button type="button" class="btn btn-success" id="post">
                                                    <i class="fa fa-check-circle" aria-hidden="true" title="Post PO"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" id="cancel">
                                                    <i class="fa fa-minus-circle" aria-hidden="true" title="Cancel PO"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
        @include('Pages.Modal.PurchaseOrderDetails')
        <script>
            $(document).ready(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $("#polist").on("click","tbody tr",function(){
                    $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
                });

                $("#po-search").on("keyup",function(){
                    $.getScript('/js/Search.js', function() {
                        Search("po-search","polist","tr","td",1);
                    });
                 });

                 $("#dtfrom").datepicker({
                    format: "mm/dd/yyyy",
                    startDate: "01/01/2019",
                    autoclose: true
                });

                $("#dtto").datepicker({
                    format: "mm/dd/yyyy",
                    startDate: $("#dtfrom").val(),
                    autoclose: true
                });

                $("#polist").on("click","tbody tr a",function(){
                    var CurrentRow = $(this).closest("tr");
                    var pocode = CurrentRow.find("td:eq(0)").text();
    
                    $.ajax({
                        type: "GET",
                        url: "/purchaseorder/details",
                        data:{
                            pocode:pocode,
                            querytype:"po"
                        },
                        success:function(result){
                            if(result.message=="success"){
                                $("#pono").val(CurrentRow.find("td:eq(1)").text());
                                $("#podate").val(CurrentRow.find("td:eq(2)").text());
                                $("#poterms").val(CurrentRow.find("td:eq(3)").text());
                                $("#posupplier").val(CurrentRow.find("td:eq(5)").text());
                                $("#poamount").val(CurrentRow.find("td:eq(4)").text());

                                $.each(JSON.parse(result.podetails), function( i, item ) {
                                    ListItems(item.stockdesc,item.unit,item.poqty,item.cost,item.amount,item.rrqty,item.qty);
                                });                   
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

                    $("#purchaseorder-details").modal("show");
                });

                function ListItems(stockdesc_,unit_,qty_,cost_,amount_,rrqty_,balqty_){
                    var po_item = "<tr>  \
                                    <td> " + stockdesc_  + " </td>  \
                                    <td> " + unit_       + " </td>  \
                                    <td class='text-right'> " + qty_        + " </td>  \
                                    <td class='text-right'> " + rrqty_        + " </td>  \
                                    <td class='text-right'><b> " + balqty_        + " </b></td>  \
                                    <td class='text-right'> " + cost_       + " </td>  \
                                    <td class='text-right'> " + amount_     + " </td>  \
                                </tr>"
                    $("#po-details tbody").append(po_item);
                };
             
                $("#search").on("click",function(){
                    var dtfrom = $("#dtfrom").val();
                    var dtto = $("#dtto").val();

                    if(dtfrom == ""){
                        Swal.fire(
                            'Please fill-in date From.',
                            '',
                            'error'
                        )
                    }
                    else if(dtto == ""){
                        Swal.fire(
                            'Please fill-in date To.',
                            '',
                            'error'
                        )
                    }
                    else {
                        $.ajax({
                            type: "GET",
                            url: "/purchaseorder/period",
                            data:{
                                dtfrom:dtfrom,
                                dtto:dtto,
                                potype: "poperiod"
                            },
                            success:function(result){
                                $("#polist tbody tr").empty();
                                if(result.message=="success"){    
                                        $.each(JSON.parse(result.purchaseorder),function(i,item){
                                                ListPurchaseorder(item.purchaseorder_code,item.pono,item.podate,item.terms,item.amount,
                                                                    item.supplier,item.deliverystatus,item.postatus)
                                        });                                      
                                }
                                else if(result.message=="nodata"){
                                        Swal.fire(
                                            "There's No data.",
                                            'Please select period.',
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
                }); 

                function ListPurchaseorder(pocode_,pono_,podate_,terms_,amount_,supplier_,_deliverystatus,_postatus){
                    var statusval;
                    if(_postatus == "U"){
                        statusval = "<div class='alert alert-secondary' style='font-size:60%;'>Unposted</div>";
                    }
                    else if(_postatus == "P" && _deliverystatus == "D"){
                        statusval = "<div class='alert alert-success' style='font-size:60%;'>Delivered</div>";
                    }
                    else if(_postatus == "P" && _deliverystatus == "I"){
                        statusval = "<div class='alert alert-warning' style='font-size:60%;'>Incomplete</div>";
                    }
                    else if(_postatus == "P" && _deliverystatus == "P"){
                        statusval = "<div class='alert alert-primary' style='font-size:60%;'>Posted</div>";
                    }
                    else if(_postatus == "C"){
                        statusval = "<div class='alert alert-danger' style='font-size:60%;'>Cancelled</div>";
                    }

                    var purchaseorder = "<tr> \
                                            <td hidden>" + pocode_ + "</td> \
                                            <td>" + pono_ + "</td> \
                                            <td>" + podate_ + "</td> \
                                            <td class='text-right'>" + terms_ + "</td> \
                                            <td class='text-right'>" + amount_ + "</td> \
                                            <td>" + supplier_ + "</td> \
                                            <td class='text-center'>" + statusval + "</td> \
                                            <td class='text-center'><a href='javascript:void()'><i class='fas fa-bars' style'color:#427bf5;'></i></a></td> \
                                            <td colspan = '2' class='text-center'> \
                                                <button type='button' class='btn btn-success' id='post'> \
                                                    <i class='fa fa-check-circle' aria-hidden='true' title='Post PO'></i> \
                                                </button> \
                                                <button type='button' class='btn btn-danger' id='cancel'> \
                                                    <i class='fa fa-minus-circle' aria-hidden='true' title='Cancel PO'></i> \
                                                </button> \
                                            </td> \
                                        </tr>";
                    $("#polist tbody").append(purchaseorder);
                };     

                $("#polist").on("click","tbody tr #post",function(){
                    var CurrRow  = $(this).closest("tr");
                    var postatus = CurrRow.find("td:eq(6)").text();
                    var pono = CurrRow.find("td:eq(0)").text();
                    
                    if(postatus == "Unposted"){
                        Swal.fire({
                            title: 'Are you sure you want to post it?',
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
                                    url: "/purchaseorder/post",
                                    data:{
                                        pono:pono
                                    },
                                    success:function(result){
                                        if(result.message=="success"){
                                            Swal.fire(
                                                'PO was posted successfully.',
                                                '',
                                                'success'
                                            ).then(function(){
                                                location.reload(); 
                                            })
                                        }
                                    }
                                })
                            }
                        })
                    }
                    else{
                        Swal.fire(
                            'Please select unposted PO only.',
                            '',
                            'warning'
                        )
                    }
                });

                $("#polist").on("click","tbody tr #cancel",function(){
                    var CurrRow = $(this).closest("tr");
                    var postatus = CurrRow.find("td:eq(6)").text();
                    var pono = CurrRow.find("td:eq(0)").text();

                    if(postatus == "Cancelled"){
                        Swal.fire(
                            'Selected PO is cancelled already.',
                            '',
                            'warning'
                        )
                    }
                    else if(postatus == "Delivered"){
                        Swal.fire(
                            'Selected PO is delivered already.',
                            "Can't cancel fully delivered PO.",
                            'warning'
                        )
                    }
                    else{
                        Swal.fire({
                            title: 'Are you sure you want to post it?',
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
                                    url: "/purchaseorder/cancel",
                                    data:{
                                        pono:pono
                                    },
                                    success:function(result){
                                        if(result.message=="success"){
                                            Swal.fire(
                                                'Selected PO was cancelled successfully.',
                                                '',
                                                'success'
                                            ).then(function(){
                                                location.reload();
                                            })
                                        }
                                    }
                                })
                            }
                        })
                    }
                });
                  
            });
        </script>
@endsection

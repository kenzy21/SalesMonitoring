@extends('layouts.Master')

@section('title','Purchase Order')

@section('content')
        <div class="col-xl-8 col-md-8 col-md-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Order</h6>
                </div>
                    <!-- Card Body -->
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
                                <button class="btn btn-primary btn-block">Search</button>
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
                                    <th>Remarks</th>
                                    <th class="text-center">Details</th>
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
                                            <td>{{ $purchaseorder->remarks }}</td>
                                            <th class="text-center"><a href="#"><i class="fas fa-bars" style="color:#427bf5;"></i></a></th>
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
                    $("#pono").val(CurrentRow.find("td:eq(1)").text());
                    $("#podate").val(CurrentRow.find("td:eq(2)").text());
                    $("#poterms").val(CurrentRow.find("td:eq(3)").text());
                    $("#posupplier").val(CurrentRow.find("td:eq(5)").text());
                    $("#poamount").val(CurrentRow.find("td:eq(4)").text());

                    $.ajax({
                        type: "GET",
                        url: "/purchaseorder/details",
                        data:{
                            pocode:pocode
                        },
                        success:function(result){
                            $.each(JSON.parse(result.message), function( i, item ) {
                                    ListItems(item.stockdesc,item.unit,item.qty,item.cost,item.amount);
                            });
                        }
                    });

                    $("#purchaseorder-details").modal("show");
             });

             function ListItems(stockdesc_,unit_,qty_,cost_,amount_){
                    var po_item = "<tr>  \
                                    <td> " + stockdesc_  + " </td>  \
                                    <td> " + unit_       + " </td>  \
                                    <td class='text-right'> " + qty_        + " </td>  \
                                    <td class='text-right'> " + cost_       + " </td>  \
                                    <td class='text-right'> " + amount_     + " </td>  \
                                </tr>"
                    $("#po-details tbody").append(po_item);
             }

        </script>
@endsection

@extends('layouts.Master')

@section('title','Stock Received')

@section('content')
    <div class="col-xl-10 col-sm-12 col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Receive</h6>
                </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <a href="{{ url('/stockreceive/create') }}" class="btn btn-primary btn-block" style="margin-bottom:10px;">Stock Receive</a>
                            </div>
                            <div class="form-group col-md-9"></div>
                        </div>       
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <div class="InputWithIcon">
                                    <input class="form-control-custom" name="dtfrom" id="dtfrom" placeholder = "From" >
                                    <i class="fas fa-calendar-alt" style="color:gray;"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="InputWithIcon">
                                    <input class="form-control-custom" name="dtfrom" id="dtto" placeholder = "To" >
                                    <i class="fas fa-calendar-alt" style="color:gray;"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <button class="btn btn-primary btn-block" id="rrperiod">Search</button>
                            </div>
                            <div class="form-group col-md-3"></div>
                        </div>     
                        <input type="text" class="form-control" id="rr-search" placeholder="SEARCH RR NO.">
                        <hr>                         
                        <div style="overflow-x:auto;">  
                            <table class="table" id="rrlist">
                                <thead class="thead-light">
                                    <th hidden>stockreceivecode</th>
                                    <th>RR No.</th>
                                    <th>RR Date</th>
                                    <th>PO No.</th>
                                    <th>Supplier</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Details</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                    <th hidden>suppcode</th>
                                    <th hidden>terms</th>
                                </thead>
                                <tbody>
                                    @foreach($rrheaders as $rrheader)
                                        <tr>
                                            <td hidden>{{ $rrheader->stockreceive_code }}</td>
                                            <td>{{ $rrheader->rrno }} </td>
                                            <td>{{ $rrheader->rrdate }}</td>
                                            <td>{{ $rrheader->pono }}</td>
                                            <td>{{ $rrheader->supplier }}</td>
                                            <td class="text-right">{{ $rrheader->netamount }}</td>
                                            <td class="text-center"><a href="javascript:void()"><i class="fas fa-bars" style="color:#427bf5;"></i></a></td>
                                            @if($rrheader->cancelled == "Y")
                                                <td class="text-center"><div class="alert alert-danger" style="font-size:60%;">Cancelled</div></td>
                                            @elseif($rrheader->posted == "Y")
                                                <td class="text-center"><div class="alert alert-success" style="font-size:60%;">Posted</div></td>
                                            @else
                                                <td class="text-center"><div class="alert alert-secondary" style="font-size:60%;">Unposted</div></td>
                                            @endif
                                            <td colspan = '2' class='text-center'> 
                                                <button type='button' class='btn btn-success' id='post'> 
                                                    <i class='fa fa-check-circle' aria-hidden='true' title='Post PO'></i> 
                                                </button> 
                                                <button type='button' class='btn btn-danger' id='cancel'> 
                                                    <i class='fa fa-minus-circle' aria-hidden='true' title='Cancel PO'></i> 
                                                </button> 
                                            </td> 
                                            <td hidden>{{ $rrheader->suppcode }}</td>
                                            <td hidden>{{ $rrheader->terms }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    @include('Pages.Modal.StockreceiveDetails')
    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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

            $("#rrlist").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });

            $("#rr-search").on("keyup",function(){
                $.getScript('/js/Search.js', function() {
                    Search("rr-search","rrlist","tr","td",1);
                });
            });

            $("#rrlist").on("click","tbody tr a",function(){
                var CurrRow = $(this).closest("tr");
                var rrcode = CurrRow.find("td:eq(0)").text();

                $.ajax({
                    type: "GET",
                    url: "/stockreceive/details",
                    data:{
                        rrcode:rrcode
                    },
                    success:function(result){
                        var parsedata = JSON.parse(result.rrdetails);

                        $("#rrno").val(parsedata[0].rrcode);
                        $("#rrdate").val(parsedata[0].rrdate);
                        $("#pono").val(parsedata[0].pono);
                        $("#rrterms").val(parsedata[0].terms);
                        $("#rrsupplier").val(parsedata[0].supplier);
                        $("#rramount").val(parsedata[0].netamount);
                        
                        $("#rr-details tbody tr").empty();
                        $.each(parsedata,function(i,item){
                            ListRRDetails(item.stockdesc,item.unit,item.qty,item.cost,item.amount);
                        });
                    }
                });

                $("#stockreceive-details").modal("show");
            });

            function ListRRDetails(stockdesc,unit,qty,cost,amount){
                var rrdetails = "<tr>  \
                                    <td>" + stockdesc + "</td> \
                                    <td>" + unit + "</td> \
                                    <td class='text-right'>" + qty + "</td> \
                                    <td class='text-right'>" + cost + "</td> \
                                    <td class='text-right'>" + amount + "</td> \
                                </tr>";

                $("#rr-details tbody").append(rrdetails);
            }

            $("#rrperiod").on("click",function(){
                if($("#dtfrom").val()==""){
                    Swal.fire(
                        'Please select From date.',
                        '',
                        'error'
                    ).then(function(){
                        $("#dtfrom").focus();
                    })
                }
                else if($("#dtto").val()==""){
                    Swal.fire(
                        'Please select To date.',
                        '',
                        'error'
                    ).then(function(){
                        $("#dtto").focus();
                    })
                }
                else{
                    $.ajax({
                        type: "GET",
                        url: "/stockreceive/period",
                        data:{
                            dtfrom: $("#dtfrom").val(),
                            dtto: $("#dtto").val(),
                            rrtype: "rrperiod"
                        },
                        success:function(result){
                            if(result.message=="success"){
                                $("#rrlist tbody tr").empty();
                                $.each(JSON.parse(result.rrheader),function(i,item){
                                    StockreceivePeriodList(item.stockreceive_code,item.rrno,item.rrdate,item.pono,
                                                            item.supplier,item.netamount);
                                });
                            }
                        }
                    });
                }
            });

            function StockreceivePeriodList(rrcode,rrno,rrdate,pono,supplier,amount){
                var rrheader = "<tr> \
                                    <td hidden>" + rrcode + "</td> \
                                    <td>" + rrno + "</td> \
                                    <td>" + rrdate + "</td> \
                                    <td>" + pono + "</td> \
                                    <td>" + supplier + "</td> \
                                    <td class='text-right'>" + amount + "</td> \
                                    <td class='text-center'><a href='javascript:void()'><i class='fas fa-bars' style='color:#427bf5;'></i></a></td> \
                                </tr>";

                $("#rrlist tbody").append(rrheader);
            };

            $("#rrlist tbody tr #post").on("click",function(){
                var CurrRow = $(this).closest("tr");
                var rrno = CurrRow.find("td:eq(0)").text();
                var rrcode = CurrRow.find("td:eq(1)").text();
                var suppcode = CurrRow.find("td:eq(9)").text();
                var terms = CurrRow.find("td:eq(10)").text();
                var rramount = CurrRow.find("td:eq(5)").text();
                var rrstatus = CurrRow.find("td:eq(7)").text();


                if(rrstatus == "Posted"){
                    Swal.fire(
                        'Selected RR No. is already posted.',
                        'RR No. ' + rrcode,
                        'warning'
                    )
                }
                else if(rrstatus == "Cancelled"){
                    Swal.fire(
                        "Can't post cancelled RR No.",
                        '',
                        'error'
                    )
                }
                else{               
                    Swal.fire({
                        title: 'Are you sure you want to post selected RR?',
                        text: "RR No. " + rrcode,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if(result.value){
                            $.ajax({
                                type: "POST",
                                url: "/stockreceive/post",
                                data:{
                                    rrno:rrno,
                                    rrcode:rrcode,
                                    suppcode:suppcode,
                                    terms:terms,
                                    rramount:rramount
                                },
                                success:function(result){
                                    if(result.message=="success"){
                                        Swal.fire(
                                            'Select RR was posted successfully.',
                                            'RR No.' + rrcode,
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

            $("#rrlist tbody tr #cancel").on("click",function(){
                var CurrRow = $(this).closest("tr");
                var rrno = CurrRow.find("td:eq(0)").text();
                var rrcode = CurrRow.find("td:eq(1)").text();
                var rrstatus = CurrRow.find("td:eq(7)").text();

                if(rrstatus == "Posted"){
                    Swal.fire(
                        'Selected RR No. is already posted.',
                        "Can't cancel posted RR",
                        'warning'
                    )
                }
                else if(rrstatus == "Cancelled"){
                    Swal.fire(
                        'Selected RR no. is already cancelled.',
                        '',
                        'warning'
                    )
                }
                else{
                    Swal.fire({
                        title: 'Are you sure you want to cancel selected RR?',
                        text: "RR No. " + rrcode,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if(result.value){
                            $.ajax({
                                type: "POST",
                                url: "/stockreceive/cancel",
                                data:{
                                    rrno:rrno,
                                    rrcode:rrcode
                                },
                                success:function(result){
                                    if(result.message=="success"){
                                        Swal.fire(
                                            'Selected RR No. is cancelled successfully.',
                                            'RR No. ' + rrcode,
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

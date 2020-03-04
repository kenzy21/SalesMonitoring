@extends('layouts.Master')

@section('title','Stock Received')

@section('content')
    <div class="col-xl-8 col-md-8 col-md-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Receive</h6>
                </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <a href="#" class="btn btn-primary btn-block" style="margin-bottom:10px;">Create</a>
                            </div>
                            <div class="form-group col-md-10"></div>
                        </div>       
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <div class="InputWithIcon">
                                    <input class="form-control-custom" name="dtfrom" id="dtfrom" placeholder = "From" >
                                    <i class="fas fa-calendar-alt" style="color:gray;"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <div class="InputWithIcon">
                                    <input class="form-control-custom" name="dtfrom" id="dtto" placeholder = "To" >
                                    <i class="fas fa-calendar-alt" style="color:gray;"></i>
                                </div>
                            </div>
                            <div class="form-group col-md-1">
                                <button class="btn btn-primary btn-block">Search</button>
                            </div>
                            <div class="form-group col-md-2"></div>
                            <div class="form-group col-md-5">
                                <input type="text" class="form-control" placeholder="SEARCH RR NO.">
                            </div>
                        </div>                              
                        <div style="overflow-x:auto;">  
                            <table class="table" id="rrlist">
                                <thead class="thead-light">
                                    <th>stockreceivecode</th>
                                    <th>RR No.</th>
                                    <th>RR Date</th>
                                    <th class="text-right">PO No.</th>
                                    <th class="text-right">Amount</th>
                                    <th>Supplier</th>
                                    <th>Remarks</th>
                                    <th class="text-center">Details</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    <script>
        $(document).ready(function(){

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

        });
    </script>
@endsection

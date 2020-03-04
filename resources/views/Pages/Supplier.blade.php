@extends('layouts.Master')

@section('title','Supplier')

@section('content')
        <div class="col-xl-12">
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Supplier</h6>
            </div>
                <!-- Card Body -->
                <div class="card-body">                 
                    <button id="supplier-create" class="btn btn-primary" style="margin-bottom:10px;">Create</button>
                    <input type="text" class="form-control" id="supplier-search" style="margin-bottom:10px; text-transform:uppercase;" placeholder="Search Supplier">
                    <div style="overflow-x:auto;">  
                       <table id="supplier" class="table">
                            <thead class="thead-light">
                                <th>Supplier Code</th>
                                <th>Supplier Name</th>
                                <th>Supplier Short Name</th>
                                <th>Terms</th>
                                <th>Supplier Address</th>
                                <th class="text-center">Action</th>
                            </thead>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td> {{ $supplier->suppcode }}</td>
                                <td> {{ $supplier->supplier_name }}</td>
                                <td> {{ $supplier->supplier_short_name }}</td>
                                <td> {{ $supplier->terms }} </td>
                                <td> {{ $supplier->supplier_address }}</td>
                                <td class="text-center"><a href="#"><i class="fas fa-edit"></i></a></td>
                            </tr>
                            @endforeach
                       </table>
                    </div>
                </div>
            </div>
        </div>
@include('Pages.Modal.SupplierCreate')
@include('Pages.Modal.SupplierEdit')
<script>
    $(document).ready(function(){

        $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

        $("#supplier-create").on("click",function(){
                $("#supplier-create-modal").modal("show");
            });

        $("#supplier").on("click","tr a",function(){
            var CurrentRow = $(this).closest("tr");
            var supplier_code,supplier_name,supplier_short_name,supplier_address,supplier_terms;

            supplier_code = CurrentRow.find("td:eq(0)").text();
            supplier_name = CurrentRow.find("td:eq(1)").text();
            supplier_short_name = CurrentRow.find("td:eq(2)").text();
            supplier_terms = CurrentRow.find("td:eq(3)").text();
            supplier_address = CurrentRow.find("td:eq(4)").text();

            $("#supplier-code").val(supplier_code);
            $("#supplier-name-edit").val(supplier_name.trim());
            $("#supplier-name-short-edit").val(supplier_short_name.trim());
            $("#supplier-address-edit").val(supplier_address.trim());
            $("#supplier-terms-edit").val(supplier_terms);

            $("#supplier-edit-modal").modal("show");

        });

        $("#supplier").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });
            
        $("#supplier-search").on("keyup",function(){
            $.getScript('/js/Search.js', function() {
                    Search("supplier-search","supplier","tr","td",1);
                });
        });

    });
</script>
@endsection
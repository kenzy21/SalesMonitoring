@extends('layouts.Master')

@section('title','Stock Master File')

@section('content')
    <div class="col-xl-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Stock Master File</h6>
            </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <button id="masterfile-create" class="btn btn-primary btn-block" style="margin-bottom:10px;">Create</button>
                        </div>
                        <div class="form-group col-md-9"></div>
                    </div>                 
                    <input type="text" class="form-control" id="masterfile-search" style="margin-bottom:10px; text-transform:uppercase;" placeholder="Search items">
                    <div style="overflow-x:auto;">  
                        <table class="table" id="stock-master-file">
                            <thead class="thead-light">
                                <th>Stockcode</th>
                                <th>Barcode</th>
                                <th>Description</th>
                                <th>Classification</th>
                                <th>Generic</th>
                                <th>Unit</th>
                                <th>Stocktag</th>
                                <th>Serialize</th>
                                <th class="text-right">Reorder Level</th>
                                <th class="text-right">Cost</th>
                                <th class="text-center">Active</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            @foreach($stockmast as $stock)
                                <tr>
                                    <td>{{ $stock->stockcode }}</td>
                                    <td>{{ $stock->barcode }} </td>
                                    <td>{{ $stock->stockdesc }}</td>
                                    <td>{{ $stock->classification }}</td>
                                    <td>{{ $stock->generic }}</td>
                                    <td>{{ $stock->unit }}</td>
                                    <td>{{ $stock->stocktag }}</td>
                                    <td>{{ $stock->serialize }} </td>
                                    <td class="text-right">{{ $stock->reorder }}</td>
                                    <td class="text-right">{{ $stock->cost }}</td>
                                    <td class="text-center">{{ $stock->active }}</td>
                                    <td class="text-center"><a href="#"><i class="fas fa-edit"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @include('Pages.Modal.MasterFileCreate')
    @include('Pages.Modal.MasterFileEdit')
    <script>

        $(document).ready(function(){

            $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            $("#masterfile-create").on("click",function(){
                $("#masterfile-create-modal").modal("show");
            });
            
            $("#stock-master-file").on("click","tbody tr",function(){
                $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
            });

            $("#masterfile-search").on("keyup",function(){
                $.getScript('/js/Search.js', function() {
                     Search("masterfile-search","stock-master-file","tr","td",2);
                    });
            });        
            
            $("#reorder").on("keypress",function(event){
                return isNumberKey(event);
            });

            $("#cost").on("keypress",function(event){
                return isNumberKey(event);
            });

            function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode != 46 && charCode > 31 
                    && (charCode < 48 || charCode > 57))
                    return false;

                return true;
            }

            $("#stock-master-file").on("click","tbody tr a",function(){
                var CurrentRow = $(this).closest("tr");
                var barcode,stockcode,stockdesc,classification,generic,
                    unit,stocktag,reorder,cost,active,serialize;
                
                stockcode = CurrentRow.find("td:eq(0)").text();
                barcode = CurrentRow.find("td:eq(1)").text();
                stockdesc = CurrentRow.find("td:eq(2)").text();
                classification = CurrentRow.find("td:eq(3)").text();
                generic = CurrentRow.find("td:eq(4)").text();
                unit = CurrentRow.find("td:eq(5)").text();
                stocktag= CurrentRow.find("td:eq(6)").text();
                serialize = CurrentRow.find("td:eq(7)").text();
                reorder = CurrentRow.find("td:eq(8)").text();
                cost = CurrentRow.find("td:eq(9)").text();
                active = CurrentRow.find("td:eq(10)").text();

                $("#stockcode-edit").val(stockcode);
                $("#barcode-edit").val(barcode.trim());
                $("#description-edit").val(stockdesc.trim());
                $("#classification-edit").val(classification);
                $("#generic-edit").val(generic);
                $("#unit-edit").val(unit);
                $("#serialize-edit").val(serialize.trim());
                $("#stocktag-edit").val(stocktag.trim());
                $("#reorder-edit").val(reorder);
                $("#cost-edit").val(cost);
                $("#active-edit").val(active);
                
                $("#masterfile-edit-modal").modal("show");
            });

        });

    </script>
@endsection
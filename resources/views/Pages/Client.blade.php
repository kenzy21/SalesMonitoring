@extends('layouts.Master')

@section('title','Cient')

@section('content') 
       <div class="col-xl-12">
          <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Client</h6>
            </div>
                <!-- Card Body -->
                <div class="card-body">                 
                    <button id="client-create" class="btn btn-primary" style="margin-bottom:10px;">Create</button>
                    <input type="text" class="form-control" id="client-search" style="margin-bottom:10px; text-transform:uppercase;" placeholder="Search Client">
                    <div style="overflow-x:auto;">  
                        <table id="client" class="table">
                            <thead class="thead-light">
                                <th>Client ID</th>
                                <th>Clent Name</th>
                                <th>Client Address</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td>{{ $client->client_code }}</td>
                                        <td>{{ $client->client_name }}</td>
                                        <td>{{ $client->client_address }}</td>
                                        <td class="text-center"><a href="#"><i class="fas fa-edit"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@include('Pages.Modal.ClientCreate')
@include('Pages.Modal.ClientEdit')
<script>
    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $("#client").on("click","tbody tr",function(){
                $(this).addClass('highlight').siblings().removeClass('highlight');
            });

        $("#client-create").on("click",function(){
                $("#client-create-modal").modal("show");
            });

        $("#client").on("click","tbody tr a",function(){
            var client_code,client_name,client_address,CurrentRow;

            CurrentRow = $(this).closest("tr");
            client_code = CurrentRow.find("td:eq(0)").text();
            client_name = CurrentRow.find("td:eq(1)").text();
            client_address = CurrentRow.find("td:eq(2)").text();

            $("#client-code").val(client_code);
            $("#client-name-edit").val(client_name);
            $("#client-address-edit").val(client_address);
            
            $("#client-edit-modal").modal("show");
        })

        $("#client-search").on("keyup",function(){
            $.getScript('/js/Search.js', function() {
                    Search("client-search","client","tr","td",1);
                });
        });

    });
</script>
@endsection
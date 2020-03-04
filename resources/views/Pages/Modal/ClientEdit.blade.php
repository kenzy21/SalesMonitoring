<div class="modal fade" id="client-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Edit Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="client-edit">
        <div class="modal-body">
            <div class="form-group">
                <input id="client-code" class="form-control form-control-sm" type="text" hidden>
            </div>
            <div class="form-group">
                <label for="client-name">Client Name</label>
                <input id="client-name-edit" name="client-name-edit" type="text" class="form-control form-control-sm" style="text-transform:uppercase;"></input>
            </div>
            <div class="form-group">
                <label for="client-address">Address</label>
                <input id="client-address-edit" name="client-address-edit" type="text" class="form-control form-control-sm" style="text-transform:uppercase;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="client-edit-data">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $("#client-edit-modal").on("show.bs.modal",function(){
            setTimeout(function(){
                $("#client-name-edit").focus();
            }, 500);
        });

    $("#client-edit-data").on("click",function(){

            var client_code,client_name,client_address,infomessage;

            client_code = $("#client-code").val();
            client_name = $("#client-name-edit").val();
            client_address = $("#client-address-edit").val();

            Swal.fire({
                title: "Are you sure you want to update it?",
                text: "",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        type: "POST",
                        url: "/client/edit",
                        data:{
                            client_code:client_code,
                            client_name:client_name,
                            client_address:client_address
                        },
                        success:function(result){
                            infomessage = result.message;
                            
                            if(infomessage=="success"){
                                Swal.fire(
                                    'Successfully saved!',
                                    'saved!',
                                    'success'
                                ).then(function(){
                                    $("#client-edit-modal").modal("hide");
                                })
                            }
                            else{
                                Swal.fire(
                                    'Please check the error below.',
                                    infomessage,
                                    'error'
                                )
                            }
                        }
                    });
                }
            })

            $("#client-edit-modal").on("hide.bs.modal",function(){
                $("#client-edit").trigger("reset");
                location.href = "/client";
            });
        });   
</script>
<div class="modal fade" id="client-create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Create Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="client-save">
        <div class="modal-body">
            <div class="form-group">
                <label for="client-name">Client Name</label>
                <input id="client-name" name="client-name" type="text" class="form-control form-control-sm" style="text-transform:uppercase;"></input>
            </div>
            <div class="form-group">
                <label for="client-address">Address</label>
                <input id="client-address" name="client-address" type="text" class="form-control form-control-sm" style="text-transform:uppercase;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="client-save-data">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#client-create-modal").on("show.bs.modal",function(){
        setTimeout(function(){
            $("#client-name").focus();
        }, 500);
    });

    $("#client-create-modal").on("hide.bs.modal",function(){
        $("#client-save").trigger("reset");
        location.href = "/client";
    });

    $("#client-save-data").on("click",function(){
        var client_name, client_address,infomessage;

        client_name = $("#client-name").val();
        client_address = $("#client-address").val();

        Swal.fire({
          title: 'Are you sure you want to save it?',
          text: '',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
            if(result.value){             
                $.ajax({
                    type: "POST",
                    url: "/client/create",
                    data:{
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
                                $("#client-save").trigger("reset");
                                setTimeout(function(){
                                    $("#client-name").focus();
                                }, 500);
                            })
                        }
                        else{
                          Swal.fire(
                            'Please check error below.',
                            infomessage,
                            'error'
                          )
                        }
                     }
                });
             }
        });        
    });
</script>
<div class="modal fade" id="supplier-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="supplier-edit">
        <div class="modal-body">
            <div class="form-group">
                <input id="supplier-code"  name="supplier-code" type="text" class="form-control form-control-sm" hidden>
            </div>
            <div class="form-group">
                <label for="supplier-name">Supplier Name</label>
                <input id="supplier-name-edit" name="supplier-name-edit" type="text" class="form-control form-control-sm" style="text-transform:uppercase;"></input>
            </div>
            <div class="form-group">
                <label for="supplier-short-name">Supplier Short Name</label>
                <input id="supplier-name-short-edit" name="supplier-name-short-edit" type="text" class="form-control form-control-sm" style="text-transform:uppercase;">
            </div>
            <div class="form-group">
                <label for="">Tems</label>
                <input type="text" class="form-control" id="supplier-terms-edit" placeholder = "No. of days">
            </div>
            <div class="form-group">
                <label for="supplier-address">Address</label>
                <input id="supplier-address-edit" name="supplier-address-edit" type="text" class="form-control form-control-sm" style="text-transform:uppercase;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="supplier-edit-data">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#supplier-edit-modal").on("show.bs.modal",function(){
        setTimeout(function(){
            $("#supplier-name-edit").focus();
        },500);
    });

    $("#supplier-edit-data").on("click",function(){

        var supplier_code,supplier_name,supplier_short_name,supplier_address,infomessage,supplier_terms;

        supplier_code = $("#supplier-code").val();
        supplier_name = $("#supplier-name-edit").val();
        supplier_short_name = $("#supplier-name-short-edit").val();
        supplier_terms = $("#supplier-terms-edit").val();
        supplier_address = $("#supplier-address-edit").val();

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
                    url: "/supplier/edit",
                    data:{
                        supplier_code:supplier_code,
                        supplier_name:supplier_name,
                        supplier_short_name:supplier_short_name,
                        supplier_address:supplier_address,
                        supplier_terms:supplier_terms
                    },
                    success:function(result){
                        infomessage = result.message
                        if(infomessage == "success"){
                            Swal.fire(
                                'Successfully updated!',
                                'saved',
                                'success'
                            ).then(function(){
                                $("#supplier-edit-modal").modal("hide");                            
                            });
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

        $("#supplier-edit-modal").on("hide.bs.modal",function(){
            $("#supplier-edit").trigger("reset");
            location.href = "/supplier";
        });
    });
    
</script>
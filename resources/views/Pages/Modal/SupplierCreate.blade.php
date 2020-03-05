<div class="modal fade" id="supplier-create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="supplier-save">
        <div class="modal-body">
            <div class="form-group">
                <label for="supplier-name">Supplier Name</label>
                <input id="supplier-name" name="supplier-name" type="text" class="form-control form-control-sm" style="text-transform:uppercase;"></input>
            </div>
            <div class="form-group">
                <label for="supplier-short-name">Supplier Short Name</label>
                <input id="supplier-name-short" name="supplier-name-short" type="text" class="form-control form-control-sm" style="text-transform:uppercase;">
            </div>
            <div class="form-group">
                <label for="">Tems</label>
                <input type="text" class="form-control" id="supplier-terms" placeholder = "No. of days">
            </div>
            <div class="form-group">
                <label for="supplier-address">Address</label>
                <input id="supplier-address" name="supplier-address" type="text" class="form-control form-control-sm" style="text-transform:uppercase;">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="supplier-save-data">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#supplier-create-modal").on("show.bs.modal",function(){
        setTimeout(function(){ 
            $("#supplier-name").focus();
        }, 500);
    });

    $("#supplier-create-modal").on("hide.bs.modal",function(){
        $("#supplier-save").trigger("reset");
    });

    $("#supplier-save-data").on("click",function(){
        
        var supplier_name, supplier_name_short,supplier_address,infomessage;

        supplier_name = $("#supplier-name").val();
        supplier_name_short = $("#supplier-name-short").val();
        supplier_address = $("#supplier-address").val();
        supplier_terms = $("#supplier-terms").val();

        Swal.fire({
            title: "Are you sure you want to save it?",
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
                    url: "/supplier/create",
                    data:{
                        supplier_name:supplier_name,
                        supplier_name_short:supplier_name_short,
                        supplier_address:supplier_address,
                        supplier_terms:supplier_terms
                    },
                    success:function(result){
                        infomessage = result.message;
                        
                        if(infomessage == "success"){
                            Swal.fire(
                                'Saved!',
                                'Successfullat saved!',
                                'success'
                            ).then(function(){
                                $("#supplier-save").trigger("reset");

                                setTimeout(function(){ 
                                    document.getElementById('supplier-name').focus();
                                    },500);
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
         });
     });

     $("#supplier-terms").on("keypress",function(event){
         return isNumberKey(event);
     });

     function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>
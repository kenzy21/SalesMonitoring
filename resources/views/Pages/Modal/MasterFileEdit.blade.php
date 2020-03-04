<div class="modal fade" id="masterfile-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Edit Master File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="masterfile-edit">
        <div class="modal-body">
            <div class="form-group">
              <input type="text" id="stockcode-edit"  class="form-control form-control-sm" hidden>
            </div>
            <div class="form-group">
              <label for="barcode">Barcode</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="barcode-edit" name="barcode-edit">
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="description-edit" name="description-edit">
            </div>
            <div class="form-group">
              <label for="classification">Classification</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="classification-edit" name="classification-edit">
            </div>
            <div class="form-group">
              <label for="generic">Generic</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="generic-edit" name="generic-edit">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="unit">Unit</label>
                    <select name="unti-edit" id="unit-edit" class="form-control form-control-sm">
                        @foreach($unitmast as $unit)
                          <option>{{ $unit->unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="stocktag">Stocktag</label>
                    <select id="stocktag-edit" name = "stocktag-edit" class="form-control form-control-sm">
                        @foreach($stocktag as $stock)
                        <option>{{ $stock->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Serialize</label>
                    <select name="serialize-edit" id="serialize-edit" class="form-control form-control-sm">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
              <label for="reorder">Reorder Level</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="reorder-edit" name="reorder-edit" value="10">
            </div>
            <div class="form-group">
              <label for="cost">Cost</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="cost-edit" name="cost-edit" value="0.00">
            </div>
            <div class="form-group">
              <label for="Active">Active</label>
              <select id="active-edit" name="active-edit" class="form-control form-control-sm">
                <option>Y</option>
                <option>N</option>
              </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="masterfile-edit-data">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $("#masterfile-edit-modal").on("show.bs.modal",function(){

      setTimeout(function(){ 
        document.getElementById('barcode-edit').focus();}, 500);

        $.ajax({
          type: "GET",
          url: "/masterfile/classification",
          success:function(result){
              var options = {
                data: result.classification,
                adjustWidth: false,
                list:{
                  match:{
                          enabled: true
                    }
                  }
                };
                $("#classification-edit").easyAutocomplete(options);
              }
            });

          $.ajax({
            type: "GET",
            url: "/masterfile/generic",
            success:function(result){
              var options = {
                data: result.generic,
                adjustWidth: false,
                list:{
                  match:{
                    enable: true
                  }
                }
              };
              $("#generic-edit").easyAutocomplete(options);
            }
          });
        });

        $("#masterfile-edit-data").on("click",function(){

            var stockcode,barcode,description,classification,generic,unit,stocktag,reorder,cost,active,serialize;

            stockcode = $("#stockcode-edit").val();
            barcode = $("#barcode-edit").val();
            description = $("#description-edit").val();
            classification = $("#classification-edit").val();
            generic = $("#generic-edit").val();
            unit = $("#unit-edit").val();
            stocktag = $("#stocktag-edit").val();
            reorder = $("#reorder-edit").val();
            cost = $("#cost-edit").val();
            active = $("#active-edit").val().trim();
            serialize = $("#serialize-edit").val();

            Swal.fire({
                title: 'Are you sure you want to update it?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {

                    $.ajax({
                        type: "POST",
                        url: "/masterfile/update",
                        data: {
                            stockcode:stockcode,
                            barcode:barcode,
                            description:description,
                            classification:classification,
                            generic:generic,
                            unit:unit,
                            stocktag:stocktag,
                            reorder:reorder,
                            cost:cost,
                            active:active,
                            serialize:serialize
                        },
                        success:function(result){
                          var infomessage;
                          infomessage = result.message;

                          if(infomessage == "success"){
                            Swal.fire(
                            'Saved!',
                            'Successfully updated!',
                            'success'
                          ).then(function(){
                            $("#masterfile-edit-modal").modal("hide");
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
        });

        $("#masterfile-edit-modal").on("hide.bs.modal",function(){
          location.href = "/masterfile";
          $("#masterfile-edit").trigger("reset");
        });
  </script>
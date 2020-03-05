<div class="modal fade" id="masterfile-create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Create Master File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="masterfile-save">
        <div class="modal-body">
            <div class="form-group">
              <label for="barcode">Barcode</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="barcode">
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="description" name="description">
            </div>
            <div class="form-group">
              <label for="classification">Classification</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="classification" name="classification">
            </div>
            <div class="form-group">
              <label for="generic">Generic</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="generic" name="generic">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="unit">Unit</label>
                    <select id="unit" name="unit" class="form-control form-control-sm">
                      @foreach($unitmast as $unit)
                        <option>{{ $unit->unit }}</option>
                      @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="stocktag">Stocktag</label>
                    <select id="stocktag" name = "stocktag" class="form-control form-control-sm">
                      @foreach($stocktag as $stock)
                        <option>{{ $stock->description }}</option>
                      @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                      <label for="">Serialize</label>
                      <select name="serialize" id="serialize" class="form-control form-control-sm">
                            <option>Y</option>
                            <option selected>N</option>
                      </select>
                </div>            
            </div>
            <div class="form-group">
              <label for="reorder">Reorder Level</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="reorder" name="reorder" value="10">
            </div>
            <div class="form-group">
              <label for="cost">Cost</label>
              <input type="text" class="form-control form-control-sm" style="text-transform:uppercase;" id="cost" name="cost" value="0.00">
            </div>
            <div class="form-group">
              <label for="Active">Active</label>
              <select id="active" name="active" class="form-control form-control-sm">
                <option selected>Y</option>
                <option>N</option>
              </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="masterfile-save-data">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#masterfile-create-modal").on("show.bs.modal",function(){
        setTimeout(function(){ 
          $("#barcode").focus();
        }, 500);

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
                $("#classification").easyAutocomplete(options);
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
                $("#generic").easyAutocomplete(options);
              }
          });
      });

      $("#masterfile-create-modal").on("hide.bs.modal",function(){
            $("#masterfile-save").trigger("reset");
            location.href = "/masterfile";
      });

      $("#masterfile-save-data").on("click",function(){

          var barcode,description,classification,generic,unit,reorder,cost,active,serialize;

          barcode = $("#barcode").val();
          description = $("#description").val();
          classification = $("#classification").val();
          generic = $("#generic").val();
          unit = $("#unit").val();
          stocktag = $("#stocktag").val();
          reorder = $("#reorder").val();
          cost = $("#cost").val();
          active = $("#active").val();
          serialize = $("#serialize").val();

          Swal.fire({
              title: 'Are you sure you want to save it?',
              text: "",
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes'
            }).then( (result) => {
                if(result.value) {
                    $.ajax({
                        type: "POST",
                        url: "/masterfile/create",
                        data:{
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
                          if (infomessage == "success"){
                            Swal.fire(
                              'Saved!',
                              'Successfully saved!',
                              'success'
                            ).then(function(){
                              $("#masterfile-save").trigger("reset");
                              $("#barcode").select();
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
            });
        });
</script>
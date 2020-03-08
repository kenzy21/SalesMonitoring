<div class="modal fade" id="serial-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Serial No.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="serial-form">
        <div class="modal-body">
            <input type="text" class="form-control" id="rowno-serial">
            <div class="form-group">
                <label for="">Stock Description</label>
                <input type="text" class="form-control" id="description-serial" style="text-transform:uppercase;" readonly>
            </div>
            <div class="form-group">
                <label for="">Quantity</label>
                <input type="text" class="form-control" id="quantity-serial" readonly>
            </div>
            <div class="form-group">
                <label for="">Serial No.<span style="font-size-adjust: 0.35;"> (Multiple serial must be separated by ";")</span></label>
                <textarea type="text" class="form-control" id="serialno" style="resize:none; text-transform:uppercase; font-size-adjust: 0.35;"></textarea>
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#serial-modal").on("shown.bs.modal",function(){
        $(this).find('#serialno').focus();
    });

    $("#serialno").on("keypress",function(){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var rowidx = $("#rowno-serial").val();
        var serial = $("#serialno").val();
        if(keycode == 13){
            $.getScript('/js/EditCurrentCell.js', function() {
                UpdateTableData("list-items",rowidx,8,serial);
                $("#serial-modal").modal("hide");
            });
        }
    });

</script>
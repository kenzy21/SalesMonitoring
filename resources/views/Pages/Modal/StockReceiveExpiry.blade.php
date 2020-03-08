<div class="modal fade" id="expiry-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Expiry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="expiry-form">
        <div class="modal-body">
            <input type="text" class="form-control" id="rowno-expiry">
            <div class="form-group">
                <label for="">Stock Description</label>
                <input type="text" class="form-control" id="description-expiry" style="text-transform:uppercase;" readonly>
            </div>
            <div class="form-group">
              <label for="">Expiry Date</label>
              <div class="InputWithIcon">
                <input class="form-control-custom" name="dtfrom" id="dtexpiry" placeholder = "Expiry Date" >
                <i class="fas fa-calendar-alt" style="color:gray;"></i>
              </div>
            </div>
            <div class="form-group">
                <label for="">Batch No.</label>
                <input type="text" class="form-control" id="batchno-expiry">
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $("#expiry-modal").on("show.bs.modal",function(){
    setTimeout(function(){
      $("#description-expiry").select();
    },500);

    $("#dtexpiry").datepicker({
        format: "mm/dd/yyyy",
        startDate: "01/01/2019",
        autoclose: true
    });
  });

  $("#batchno-expiry").on("keypress",function(){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    var rowidx = $("#rowno-expiry").val();
    var batchno = $("#batchno-expiry").val();
    var expiry = $("#dtexpiry").val();
    var expiry_value = expiry + "-" + batchno; 

    if(keycode == 13){
        $.getScript('/js/EditCurrentCell.js', function() {
          UpdateTableData("list-items",rowidx,9,expiry_value);
          $("#expiry-modal").modal("hide");
        });
    }
  });
</script>
<div class="modal fade" id="purchaseorder-edititem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit-item">
        <div class="modal-body">
            <input type="text" class="form-control" id="rowno" hidden>
            <div class="form-group">
                <label for="">Stock Description</label>
                <input type="text" class="form-control" id="description-edititem" style="text-transform:uppercase;" readonly>
            </div>
            <div class="form-group">
              <label for="">Unit</label>
              <input type="text" class="form-control" id="unit-edititem" readonly>
            </div>
            <div class="form-group">
                <label for="">Cost</label>
                <input type="text" class="form-control" id="cost-edititem">
            </div>
            <div class="form-group">
                <label for="">Quantity</label>
                <input type="text" class="form-control" id="quantity-edititem">
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#purchaseorder-edititem").on("show.bs.modal",function(){
            setTimeout(function(){
                $("#quantity-edititem").select();
            },500);
    });

    $("#purchaseorder-edititem").on("hide.bs.modal",function(){
            $("#edit-item").trigger("reset");
    });

    $("#quantity-edititem").keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            var rowidx,costedit,qtyedit,qtyamount;
              
            rowidx = $("#rowno").val();
            cost = $("#cost-edititem").val();
            qty = $("#quantity-edititem").val();
            amount = parseFloat(cost.replace(/,/ig,'')) * parseFloat(qty.replace(/,/ig,''))

            if(keycode == 13){
                if(qty.trim()=="" || parseFloat(qty)==0){
                      Swal.fire(
                        'Please provide quantity.',
                        '',
                        'warning'
                      )
                }
                else if(cost.trim()=="" || parseFloat(cost)==0){
                      Swal.fire(
                          'Please provide cost.',
                          '',
                          'warning'
                        )
                }
                else{
                    $.getScript('/js/EditCurrentCell.js', function() {
                            UpdateTableData("list-items",rowidx,4,qty);
                            UpdateTableData("list-items",rowidx,5,accounting.formatMoney(cost, { symbol: "",  format: "%v %s" }));
                            UpdateTableData("list-items",rowidx,6,accounting.formatMoney(amount, { symbol: "",  format: "%v %s" }));
                            SetTotalAmount();

                            $("#purchaseorder-edititem").modal("hide");
                        });
                }

            }
      });
</script>
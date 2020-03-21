<div class="modal fade" id="cashtransaction-edititem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Cash Transaction Quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="cashedititem">
        <div class="modal-body">
            <input type="text" class="form-control" id="rowno-cashedititem" hidden>
            <div class="form-group">
                <label for="">Stock Description</label>
                <input type="text" class="form-control" id="description-cashedititem" style="text-transform:uppercase;" readonly>
            </div>
            <div class="form-group">
              <label for="">Unit</label>
              <input type="text" class="form-control" id="unit-cashedititem" readonly>
            </div>
            <div class="form-group">
                <label for="">Price</label>
                <input type="text" class="form-control" id="price-cashedititem" readonly>
            </div>
            <div class="form-group">
                <label for="">Quantity</label>
                <input type="text" class="form-control" id="quantity-cashedititem">
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $("#cashtransaction-edititem").on("hide.bs.modal",function(){
        document.getElementById("cashedititem").reset();
    });

    $("#cashtransaction-edititem").on("show.bs.modal",function(){
        setTimeout(function(){
            $("#quantity-cashedititem").select();
        },500);
    });

    $("#quantity-cashedititem").on("keyup",function(){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var rowidx = $("#rowno-cashedititem").val();
        var qty = $("#quantity-cashedititem").val();
        var price = $("#price-cashedititem").val().replace(",","");
        var total = parseFloat(qty) * parseFloat(price)

        if(keycode == 13){
            if(qty == "" || qty == 0){
                Swal.fire(
                    'Please provide a quantity.',
                    '',
                    'warning'
                ).then(function(){
                    $("#quantity-cashedititem").select();
                })
            }
            else{
            $.getScript('/js/EditCurrentCell.js', function() {
                UpdateTableData("trans-list",rowidx,4,qty);
                UpdateTableData("trans-list",rowidx,5,accounting.formatMoney(price, { symbol: "",  format: "%v %s" }));
                UpdateTableData("trans-list",rowidx,6,accounting.formatMoney(total, { symbol: "",  format: "%v %s" }));
                SetRow();
                $("#cashtransaction-edititem").modal("hide");      
            });
          }
        }
    });

</script>
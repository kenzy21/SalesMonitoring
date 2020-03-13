<div class="modal fade" id="stockreceive-edititem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Stock Receive Edit Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit-item">
        <div class="modal-body">
            <input type="text" class="form-control" id="rowno">
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
            <input type="text" class="form-control" id="qty" hidden>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    $("#stockreceive-edititem").on("show.bs.modal",function(){
        setTimeout(function(){
            $("#quantity-edititem").select();
        },500)
    });

    $("#quantity-edititem").on("keypress",function(event){
        return isNumberKey(event);
    });

    $("#cost-edititem").on("keypress",function(event){
        return isNumberKey(event);
    });

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
            return false;
            return true;
    };


    $("#quantity-edititem").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var rowidx,cost,qty,amount;
          
        rowidx = $("#rowno").val();
        cost = $("#cost-edititem").val();
        qty = $("#quantity-edititem").val();
        qty_val = $("#qty").val();
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
            else if(parseFloat(qty) >parseFloat(qty_val)){
                Swal.fire(
                    'Qty must not be greater that PO qty or PO outstanding balance',
                    '',
                    'warning'
                ).then(function(){
                    $("#quantity-edititem").select();
                })
            }
            else{
                $.getScript('/js/EditCurrentCell.js', function() {
                    UpdateTableData("list-items",rowidx,4,qty);
                    UpdateTableData("list-items",rowidx,5,accounting.formatMoney(cost, { symbol: "",  format: "%v %s" }));
                    UpdateTableData("list-items",rowidx,6,accounting.formatMoney(amount, { symbol: "",  format: "%v %s" }));
                    SetTotalAmount();

                    $("#stockreceive-edititem").modal("hide");
                });
             }
          }
       });

       function SetTotalAmountDiscount(){
            var gross = $("#gross-amount").val();
            var discount = $("#discount-amount").val();
            var net = parseFloat(gross.replace(",","")) - parseFloat(discount.replace(",",""));
            $("#net-amount").val(accounting.formatMoney(net, { symbol: "",  format: "%v %s" }));
        };

        function SetTotalAmount(){
            $.getScript('/js/GetTotalAmount.js',function(){
                var totalamount = TotalAmount("#list-items tr",6);
                $("#gross-amount").val(totalamount);
                SetTotalAmountDiscount();
            });
        };
</script>
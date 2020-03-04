<div class="modal fade" id="purchaseorder-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Purchase Order Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="podetails">
        <div class="modal-body">
            <div style="background-color:#e8eced; padding-left:3%; padding-top:2%; padding-right:3%; padding-bottom:2%; border-radius: 25px;">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Purchase No.</label>
                        <input type="text" id="pono" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">PO Date</label>
                        <input type="text" id="podate" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                    <div class="form-group col-md 4">
                        <label for="">Terms</label>
                        <input type="text" id="poterms" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Supplier</label>
                    <input type="text" id="posupplier" class="form-control form-control-sm" style="background:white;" readonly>
                </div>
                <div class="form-group">
                    <label for="">Amount</label>
                    <input type="text" id="poamount" class="form-control form-control-sm" style="background:white;" readonly>
                </div>
            </div>
            <hr>
            <table class="table" id="po-details">
                <thead class="thead-light">
                    <th>Description</th>
                    <th>Unit</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Cost</th>
                    <th class="text-right">Amount</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $("#purchaseorder-details").on("hide.bs.modal",function(){
        $("#podetails").trigger("reset");
    });

    $("#purchaseorder-details").on("show.bs.modal",function(){
        $("#po-details tbody tr").remove();
    });

    $("#po-details").on("click","tbody tr",function(){
        $(this).addClass('RowHighlight').siblings().removeClass('RowHighlight');
    });
</script>
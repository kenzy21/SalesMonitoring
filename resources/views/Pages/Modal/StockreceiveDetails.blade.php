<div class="modal fade" id="stockreceive-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Stock Received Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="rrdetails">
        <div class="modal-body">
            <div style="background-color:#e8eced; padding-left:3%; padding-top:2%; padding-right:3%; padding-bottom:2%; border-radius: 25px;">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="">RR No.</label>
                        <input type="text" id="rrno" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">RR Date</label>
                        <input type="text" id="rrdate" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">PO No.</label>
                        <input type="text" id="pono" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                    <div class="form-group col-md 3">
                        <label for="">Terms</label>
                        <input type="text" id="rrterms" class="form-control form-control-sm" style="background:white;" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Supplier</label>
                    <input type="text" id="rrsupplier" class="form-control form-control-sm" style="background:white;" readonly>
                </div>
                <div class="form-group">
                    <label for="">Amount</label>
                    <input type="text" id="rramount" class="form-control form-control-sm" style="background:white;" readonly>
                </div>
            </div>
            <hr>
            <table class="table" id="rr-details">
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
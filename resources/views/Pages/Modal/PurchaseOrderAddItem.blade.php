<div class="modal fade" id="purchaseorder-additem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add-item">
        <div class="modal-body">
            <div class="form-group">
                <label for="">Stock Description</label>
                <input type="text" class="form-control" id="description-additem" style="text-transform:uppercase;">
            </div>
            <div class="form-group">
              <label for="">Unit</label>
              <input type="text" class="form-control" id="unit-additem" readonly>
            </div>
            <div class="form-group">
                <label for="">Cost</label>
                <input type="text" class="form-control" id="cost-additem">
            </div>
            <div class="form-group">
                <label for="">Quantity</label>
                <input type="text" class="form-control" id="quantity-additem">
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
<script>

    var stockcode,stockdesc,unit,cost,quantity;

    $("#purchaseorder-additem").on("show.bs.modal",function(){
        setTimeout(function(){
            $("#description-additem").select();
        },500);

        $.ajax({
          type: "GET",
          url: "/purchaseorder/masterfile",
          success:function(result){
              var options = {
                data:result.masterfile,
                getValue: "stockdesc",
                adjustWidth: false,
                placeholder: "Stock Description",
                list:{
                    match:{
                        enabled: true
                      },
                      onChooseEvent:function() {
                        stockcode = $("#description-additem").getSelectedItemData().stockcode;
                        stockdesc = $("#description-additem").getSelectedItemData().stockdesc;
                        unit = $("#description-additem").getSelectedItemData().unit;
                        cost = $("#description-additem").getSelectedItemData().currcost;

                      $("#unit-additem").val(unit);
                      $("#cost-additem").val(accounting.formatMoney(cost, { symbol: "",  format: "%v %s" }));
                      $("#quantity-additem").val(1);
                      $("#quantity-additem").select();
                    }
                  }
                };
                $("#description-additem").easyAutocomplete(options);
              }
          });
      });

      $("#quantity-additem").keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            
            if(keycode == '13'){
                quantity = $("#quantity-additem").val();
                cost = $("#cost-additem").val();

                if(quantity.trim()=="" || parseFloat(quantity)==0){
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
                      InsertIntoTable(stockcode,stockdesc,unit,cost,quantity);
                      $("#purchaseorder-additem").modal("hide");
                      $("#add-item").trigger("reset");
                }      
            }
      });

      function InsertIntoTable(stockcode_,stockdesc_,unit_,cost_,quantity_){
          var total = parseFloat(cost_.replace(/,/ig,'')) * quantity_;
          total = accounting.formatMoney(total, { symbol: "",  format: "%v %s" });
          var additem = "<tr> \
                              <td> " +  "" + " </td> \
                              <td hidden> " +  stockcode_ + " </td> \
                              <td> " +  stockdesc_ + " </td> \
                              <td> " +  unit_ + " </td> \
                              <td class='text-right'> " + quantity_  + " </td> \
                              <td class='text-right'> " +  cost_ + " </td> \
                              <td class='text-right'> " + total + "  </td> \
                              <td class='text-center'> \
                                  <a href='#' id='remove'><span><i class='fas fa-trash-alt' style='color:#427bf5;'></i></span><a/> \
                                  <span>|</span> \
                                  <a href='#' id='edit'><span><i class='fas fa-edit' style='color:#427bf5;'></i></span></a> \
                              </td> \
                        </tr>";
        
          $("#list-items tbody").append(additem);
          SetRow();     
      };

      function SetRow(){
          $('#list-items tbody tr').each(function(idx){
                $(this).children(":eq(0)").html(idx + 1);
                SetTotalAmount();
          });
      };

      $("#list-items").on("click","tbody td #remove",function(){
        Swal.fire({
                title: "Are you sure you want to remove it?",
                text: "",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
              if(result.value){
                  if($("#list-items tbody tr").length == 1){
                        $("#total-amount").val("0.00")
                  }
                  $(this).closest("tr").remove();
                  SetRow();
                }
            });
      });

    function SetTotalAmount(){
        $.getScript('/js/GetTotalAmount.js',function(){
            var totalamount = TotalAmount("#list-items tr",6);
            $("#total-amount").val(totalamount);
        });
    };
</script>
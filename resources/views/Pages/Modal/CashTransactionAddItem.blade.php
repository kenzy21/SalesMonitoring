<div class="modal fade" id="cashtransaction-additem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <input type="text" class="form-control" id="description-additem" style="text-transform:uppercase;" placeholder="Stock Description">
            </div>
            <div class="form-group">
              <label for="">Unit</label>
              <input type="text" class="form-control" id="unit-additem" readonly>
            </div>
            <div class="form-group">
                <label for="">Price</label>
                <input type="text" class="form-control" id="price-additem" readonly>
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
    var stockcode,stcockdesc,unit,price,qty,total;

    $("#cashtransaction-additem").on("show.bs.modal",function(){

        document.getElementById("add-item").reset(); 

        setTimeout(function(){
            $("#description-additem").select();
        },500);

        $.ajax({
            type: "GET",
            url: "/stocklist",
            success:function(result){
                var option = {
                    data:result.stocklist,
                    getValue: "stockdesc",
                    adjustWidth: false,
                    list:{
                        match:{
                            enabled: true
                        },
                        onChooseEvent:function(){
                            stockcode = $("#description-additem").getSelectedItemData().stockcode;
                            stockdesc = $("#description-additem").getSelectedItemData().stockdesc;
                            unit = $("#description-additem").getSelectedItemData().unit;
                            price = $("#description-additem").getSelectedItemData().price;

                            $("#unit-additem").val(unit);
                            $("#price-additem").val(price);
                            $("#quantity-additem").val(1);
                            $("#quantity-additem").select();
                        }
                    }
                }
                $("#description-additem").easyAutocomplete(option);
            }
        });

    });

    function SetRow(){
        $('#trans-list tbody tr').each(function(idx){
            $(this).children(":eq(0)").html(idx + 1);
        });
        SetTotalAmount();
    };

    function SetTotalAmount(){
        $.getScript('/js/GetTotalAmount.js',function(){
            var totalamount = TotalAmount("#trans-list tr",6);
            $("#gross-amount").val(totalamount);
            $("#net-amount").val(totalamount);
        });
    };

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
            return false;
        return true;
    };

    $("#quantity-additem").on("keypress",function(event){
        return isNumberKey(event);
    });

    $("#payment-amount").on("keypress",function(event){
        return isNumberKey(event);
    });

    $("#disc-amount").on("keypress",function(event){
        return isNumberKey(event);
    });

    $("#quantity-additem").keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        
        var qty = $("#quantity-additem").val();

        if(keycode == '13'){
            if(qty.trim()=="" || parseFloat(qty)==0){
                    Swal.fire(
                        'Please provide quantity.',
                        '',
                        'warning'
                    ).then(function(){
                        $("#quantity-additem").select();
                    })
            } 
            else{
                  InsertIntoTable(stockcode,stockdesc,unit,price,qty);
                  $("#cashtransaction-additem").modal("hide");
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
                                  <a href='javascript:void()' id='remove'><span><i class='fas fa-trash-alt' style='color:#427bf5;'></i></span><a/> \
                                  <span>|</span> \
                                  <a href='javascript:void()' id='edit'><span><i class='fas fa-edit' style='color:#427bf5;'></i></span></a> \
                              </td> \
                        </tr>";
        
          $("#trans-list tbody").append(additem);
          SetRow();     
      };
</script>

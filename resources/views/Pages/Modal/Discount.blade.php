<div class="modal fade" id="cashtransaction-discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#499be3; color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Discount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="discount-form">
        <div class="modal-body">
            <div class="form-group">
                <label for="">Due Amount</label>
                <input type="text" class="form-control form-control-sm" id="dueamount" readonly>
            </div>
            <div class="form-group">
                <select class="form-control form-control-sm" id="discount-type">
                    <option id="1">SELECT DISCOUNT</option>
                    @foreach($discounts as $discount)
                        <option>{{ $discount->discount_ }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
            <label for="">Discount Amount</label>
                <input type="text" class="form-control form-control-sm" id="disc-amount">
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
<script>

    $("#cashtransaction-discount").on("show.bs.modal",function(){
        $("#disc-amount").val("0.00");
    });

    document.getElementById("1").selected=true;
   
    $("#disc-amount").attr('readonly', true); 

    $("#discount-type").change(function(){
        var selectedidx = $("#discount-type option:selected").index();

        if (selectedidx == 0){
            Swal.fire(
                'Please select discount type.',
                '',
                'error'
            )
        }
        else{
            var disctype =  $("#discount-type option:selected").text();
            var discountamount;
            
            $.ajax({
                type: "GET",
                url: "/discount",
                data:{
                    discounttype:disctype.split("-",1)
                },
                success:function(result){
                    if(result.discounttype == "Y"){
                        var dueamount = $("#dueamount").val();
                        discountamount = parseFloat(result.discount/100) * parseFloat(dueamount)

                        $("#disc-amount").attr('readonly', true); 
                        $("#disc-amount").val(discountamount.toFixed(2));
                    }
                    else{
                        $("#disc-amount").val("0.00");
                        $("#disc-amount").attr('readonly', false); 
                    }
                    $("#disc-amount").select();
                }
            });
        }
    })

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
            return false;
        return true;
    };

    $("#disc-amount").on("keypress",function(event){
        return isNumberKey(event);
    });

    $("#disc-amount").on("keypress",function(){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        
        var dueamount = $("#dueamount").val().replace(",","");

        var amount = $("#disc-amount").val();

        var netamount;

        if(keycode == '13'){
            if(amount == "" || parseFloat(amount) == 0){
                Swal.fire(
                    'Please check the discount amount.',
                    '',
                    'error'
                )
            }
            else if(parseFloat(amount) > parseFloat(dueamount)){
                Swal.fire(
                    'Please check the discount amount.',
                    'Discount amount must not be greater than due amount.',
                    'error'
                ).then(function(){
                    $("#disc-amount").select();
                })
            }
            else{
                netamount = dueamount - amount;

                $("#discount-amount").val(accounting.formatMoney($("#disc-amount").val(), { symbol: "",  format: "%v %s" }));
                $("#net-amount").val(accounting.formatMoney(netamount, { symbol: "",  format: "%v %s" }));
                $("#cashtransaction-discount").modal("hide");
            }
        }
    })
</script>
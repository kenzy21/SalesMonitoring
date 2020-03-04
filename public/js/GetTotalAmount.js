function TotalAmount(table_name_,idx){
    var totalamount = 0;
        $(table_name_).each(function(){
            var amount = $(this).find("td:eq("+ idx +")").text().replace(/,/ig,'');
            if(amount!==""){
                totalamount +=  parseFloat(amount);
            }
        });
        return accounting.formatMoney(totalamount, { symbol: "",  format: "%v %s" });
};
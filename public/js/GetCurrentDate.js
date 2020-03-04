function GetTodayDate() {
    var month,curr_date;
    
    month = ["JAN","FEB","MAR","APR","MAY","JUN","JUL", 
                        "AUG","SEP","OCT","NOV","DEC"];
    curr_date = new Date();
    currentDate =  month[curr_date.getMonth()] + "-" + curr_date.getDate() + "-" + curr_date.getFullYear();

    return currentDate;
 }
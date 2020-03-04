function UpdateTableData(table_name,row_,cell_,data_){
    document.getElementById(table_name).rows[parseInt(row_)].cells[parseInt(cell_)].innerHTML = data_;
}
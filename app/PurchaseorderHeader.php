<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseorderHeader extends Model
{
    protected $table = "purchaseorder_header";
    public $primaryKey = "purchaseorder_code";
}

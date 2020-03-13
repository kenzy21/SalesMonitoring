<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockreceiveHeader extends Model
{
    protected $table = "stockreceive_header";
    public $primaryKey = "stockreceive_code";
}

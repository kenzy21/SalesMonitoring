<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = "supplier";
    public $primaryKey = "suppcode";
    public $timestamps = true;

}

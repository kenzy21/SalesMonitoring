<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Masterfile extends Model
{
    protected $table = 'masterfile';
    public $primaryKey = 'stockcode';
    public $timestamps = true;
}

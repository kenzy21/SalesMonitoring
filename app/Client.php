<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "clients";
    public $primaryKey = "client_code";
    public $timestamps = true;
}

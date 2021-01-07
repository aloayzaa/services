<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = ['date','compra','venta'];
    protected $hidden = ['id'];
    public $timestamps = false;
}

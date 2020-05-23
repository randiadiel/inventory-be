<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $incrementing = false;
    protected $fillable = ['id','name', 'qty', 'price', 'date_registered', 'box_status', 'location'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

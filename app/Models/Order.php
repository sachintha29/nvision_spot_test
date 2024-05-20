<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'order_value',
        'order_date',
        'order_status',
        'process_id'
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->id) {
                $order->id = str_pad(static::max('id') + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function setIdAttribute($value)
    {
        $this->attributes['id'] = str_pad($value, 4, '0', STR_PAD_LEFT);
    }
}

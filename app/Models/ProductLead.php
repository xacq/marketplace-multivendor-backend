<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'vendor_id',
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    const STATUSES = [
        'new'        => 'Nuevo',
        'contacted'  => 'Contactado',
        'in_process' => 'En Proceso',
        'sold'       => 'Vendido',
        'lost'       => 'Perdido',
        'paused'     => 'Pausado',
        'won'        => 'Ganado',
    ];

    const STATUS_COLORS = [
        'new'        => 'blue',
        'contacted'  => 'yellow',
        'in_process' => 'orange',
        'sold'       => 'green',
        'lost'       => 'red',
        'paused'     => 'gray',
        'won'        => 'emerald',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

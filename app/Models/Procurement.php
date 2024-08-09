<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'procurement_number',
        'email',
        'quantity',
        'request_date',
        'delivery_date',
        'purpose',
        'user_id',
        'asset_type_id',
        'brand_id',
    ];
}

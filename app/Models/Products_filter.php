<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_filter extends Model
{
    protected $table = 'products_filters';

    protected $primaryKey = 'products_filters_id';
    use HasFactory;
}

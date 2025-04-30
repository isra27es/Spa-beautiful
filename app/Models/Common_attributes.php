<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common_attributes extends Model
{
    protected $table = 'common_attributes';

    protected $primaryKey = 'common_attributes_id';
    
    use HasFactory;
}

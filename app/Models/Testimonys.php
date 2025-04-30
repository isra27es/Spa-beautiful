<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonys extends Model
{
    protected $table = 'testimonys';

    protected $primaryKey = 'testimonys_id';
    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_sections extends Model
{
    protected $table = 'service_sections';

    protected $primaryKey = 'service_sections_id';
    use HasFactory;
}

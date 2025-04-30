<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package_services extends Model
{
    protected $table = 'package_services';

    protected $primaryKey = 'package_services_id';
    use HasFactory;
}

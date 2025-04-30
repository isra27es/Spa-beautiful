<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unregistered_user extends Model
{
    protected $table = 'unregistered_users';

    protected $primaryKey = 'unregistered_users_id';
    use HasFactory;
}

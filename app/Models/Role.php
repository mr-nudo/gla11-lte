<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const COMPANY = 3;
    const EMPLOYEE = 4;
}

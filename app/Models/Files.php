<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    use HasFactory;
}

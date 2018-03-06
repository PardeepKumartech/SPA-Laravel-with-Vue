<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Validator;
class Task extends Model
{
   

    protected $fillable = [
        'name',
        'user_id',
        'description',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
   
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}

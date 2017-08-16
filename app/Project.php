<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $dates = ['last_update'];

    protected $casts = ['auto' => 'boolean'];
}

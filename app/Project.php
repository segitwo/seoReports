<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $dates = ['last_update', 'start_date'];

    protected $casts = ['auto' => 'boolean'];
}

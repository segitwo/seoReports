<?php

namespace App\Template;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    public function blocks(){
        return $this->hasMany('App\Template\TemplateBlock');
    }
}

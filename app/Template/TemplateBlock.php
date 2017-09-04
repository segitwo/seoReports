<?php

namespace App\Template;

use Illuminate\Database\Eloquent\Model;

class TemplateBlock extends Model
{
    public function totalVisitsBlock(){
        return $this->hasOne('App\Template\TotalVisitsBlock');
    }

    public function template(){
        return $this->belongsTo('App\Template\Template');
    }

    public function getOne($className) {
        return $this->hasOne('App\Template\\' . $className);
    }
}

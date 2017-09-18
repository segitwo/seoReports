<?php

namespace App\Template;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class TemplateBlockExtension extends Model
{
    public $timestamps = false;

    protected $siteKey = "";
    protected $rankingKey = "";
    protected $montharr = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
    protected $today;
    protected $prevDay;
    protected $days;
    protected $reportId;

    public function templateBlock(){
        return $this->belongsTo('App\Template\TemplateBlock');
    }

    public function getProperties(){

        $properties = $this->listProperties();
        foreach ($properties as $key => $property) {
            $properties[$key]['value'] = $this->getAttribute($key);
        }

        return $properties;
    }

    abstract public function listProperties();

    public function getData($requestData, $reportId){

        $siteKey = $requestData['siteid'];
        $rankingKey = $requestData['se_ranking'];

        $this->siteKey = $siteKey;
        $this->rankingKey = $rankingKey;

        $this->today = Carbon::parse($requestData['date']);
        $this->prevDay = clone $this->today;
        $this->prevDay->modify('-1 month');

        $this->days = [$this->prevDay->format('Y-m-d'), $this->today->format('Y-m-d')];

        $this->reportId = $reportId;
    }
}

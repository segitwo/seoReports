<?php

namespace App\Stats;

use App\Project;
use Carbon\Carbon;

class AutoText {

    private $today;
    private $prevDay;

    public function __construct(Carbon $today){
        $this->today = $today;
        $this->prevDay = clone $today;
        $this->prevDay->modify('-1 month');
    }
    
    public function getAutoText($work = "", $requestData){

        $textPlaceholders = [
            'prevDay' => $this->prevDay->format('d.m.Y'),
            'today' => $this->today->format('d.m.Y'),
            'work' => ''
        ];

        $period = $requestData['period'];
        $textPlaceholders['dop'] = 0;

        if($work == "dop"){
            $period = $requestData['dop_work'];
            $textPlaceholders['dop'] = 1;
        }

        if($period != 0){
            switch($period){
                case 1:
                    if(isset($requestData['work'])){
                        switch($requestData['work']){
                            case 'Рерайт':
                                $textPlaceholders['work'] = __('text.work_rewrite');
                                break;
                            default:
                                $textPlaceholders['work'] = __('text.work_default');
                                break;
                        }
                    }

                    if(isset($requestData['write_text'])){
                        $support_text_rows = explode("\n", $requestData['write_text']);
                        $worklist = "";
                        foreach($support_text_rows as $row){
                            $worklist .= view('reports.xml.listRow', ["val" => $row])->render();
                        }
                        $textPlaceholders['worklist'] = $worklist;
                    }

                    if(isset($requestData['hasPositions'])){
                        switch($requestData['hasPositions']){
                            case 'Новое продвижение':
                                $textPlaceholders['hasPositions1'] = __('text.positions_new_1');
                                $textPlaceholders['hasPositions2'] = __('text.positions_new_2');
                                break;
                            case 'Есть позиции':
                                $textPlaceholders['hasPositions1'] = __('text.positions_old_1');
                                $textPlaceholders['hasPositions2'] = __('text.positions_old_2');
                                break;
                        }
                    }

                    return view('reports.xml.text.1month', $textPlaceholders)->render();
                    break;

                case 2:
                    if(isset($requestData['work'])){
                        switch($requestData['work']) {
                            case 'Первичный аудит':
                                return view('reports.xml.text.2month', ['work' => __('text.first_audit')])->render();
                                break;
                            case 'Вторичный аудит':
                                return view('reports.xml.text.2month', ['work' => __('text.second_audit')])->render();
                                break;
                        }
                    }
                    break;

                case 3:
                    return view('reports.xml.text.3month', $textPlaceholders)->render();
                    break;
                case 4:
                    return view('reports.xml.text.4month', $textPlaceholders)->render();
                    break;
                case 5:
                    return view('reports.xml.text.5month', $textPlaceholders)->render();
                    break;
                case 6:
                    return view('reports.xml.text.6month', $textPlaceholders)->render();
                    break;
                case 7:
                    if(isset($requestData['links'])){
                        $support_text_rows = explode("\n", $requestData['links']);
                        $worklist = "";
                        foreach($support_text_rows as $row){
                            $worklist .= view('reports.xml.listRow', ["val" => $row])->render();
                        }
                        $textPlaceholders['links'] = $worklist;
                    }

                    return view('reports.xml.text.7month', $textPlaceholders)->render();
                    break;
                case 8:
                    return view('reports.xml.text.8month', $textPlaceholders)->render();
                    break;
                case 9:
                    return view('reports.xml.text.9month', $textPlaceholders)->render();
                    break;
                case 10:
                    return view('reports.xml.text.10month', $textPlaceholders)->render();
                    break;
                case 11:
                    return view('reports.xml.text.11month', $textPlaceholders)->render();
                    break;
                case 12:
                    return view('reports.xml.text.12month', $textPlaceholders)->render();
                    break;
            }
        }
    }
    
    public function getNextWorkText($request){
        $project = Project::find($request['id']);
        $projectLife = Carbon::parse($project->start_date)->modify('-1 day')->diffInYears(Carbon::today());

        $work = $request['next_work'];
        switch($work){
            case 1:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_1', [
                    'repeat' => $projectLife ? __('text.repeat') : ''
                ])])->render();
                break;
            case 2:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_2')])->render();
                break;
            case 3:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_3')])->render();
                break;
            case 4:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_4')])->render();

                break;
            case 5:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_5')])->render();

                break;
            case 6:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_6')])->render();
                break;
            case 7:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_7')])->render();
                break;
            case 8:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_8')])->render();
                break;
            case 9:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_9')])->render();
                break;
            case 10:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_10')])->render();
                break;
            case 11:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_11')])->render();
                break;
            case 12:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_12')])->render();
                break;
            case 13:
                return view('reports.xml.paragraph', ["val" => __('text.next_work_13')])->render();
                break;


        }
    }
    
    public function getSupportText($support = [], $supportText = ''){

        if(!count($support)){return "";}

        $outputList =
            view('reports.xml.paragraph', ["val" => ""])->render() .
            view('reports.xml.paragraph', ["val" => "В рамках плановых работ по контент-сопровождению:"])->render();
        
        if(in_array('Отзывы на сайте', $support) && in_array('Отзывы на стор. ресурсе', $support)){
            unset($support[array_search('Отзывы на сайте', $support)]);
            unset($support[array_search('Отзывы на стор. ресурсе', $support)]);
            $support[] = "Отзывы везде";
        }
        
        foreach($support as $supp){
            switch($supp) {
                case "Обратная связь":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_feedback')])->render();
                    break;
                case "Новости":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_news')])->render();
                    break;
                case "Статьи":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_articles')])->render();
                    break;
                case "Фотогалерея":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_photos')])->render();
                    break;
                case "Отзывы на сайте":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_reviews')])->render();
                    break;
                case "Отзывы на стор. ресурсе":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_side_reviews')])->render();
                    break;
                case "Отзывы везде":
                    $outputList .= view('reports.xml.listRow', ["val" => __('text.support_all_reviews')])->render();
                    break;
            }
        }

        if($supportText != ''){
            $support_text_rows = explode("\n", $supportText);
            $worklist = "";
            foreach($support_text_rows as $row){
                $worklist .= view('reports.xml.listRow', ["val" => $row])->render();
            }
            $worklist = view('reports.xml.paragraph', ["val" => ""])->render() . view('reports.xml.paragraph', ["val" => __('text.support_list_title')])->render() . $worklist;
        }

        return $outputList . $worklist . view('reports.xml.paragraph', ["val" => ""])->render();
    }
    
    public function getGeneralStatistic($today) {
        
        $prevDay = clone $today;
        $prevDay->modify('-1 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];
        //$newMonth = ($today->format('n') + 9) % 12;
        //die($today->format('d'));
        
        $m = new YMetrika("traffic", "", $metric = "", $days, $this->siteKey);
        $mData = $m->getData();
        
        $firstDayInMonth = 0;
        $resultData = [];
        $totalGuests = 0;
        $totalVews = 0;
        $totalVisits = 0;
        
        foreach($mData->data as $idx => $data) {
            
            //date
            $dateArray = (array)$data->dimensions[0];
            
            $date = new DateTime($dateArray['name']);
            
            if($firstDayInMonth == 0){
                $firstDayInMonth = $date;
            }
        
            if($firstDayInMonth->format('d') == $date->format('d') && $idx != 0){
                //$resultData["periods"]["'" . $montharr[$firstDayInMonth->format("m") - 1] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")'"] = [$totalGuests , $totalVews, $totalVisits];
                $resultData["periods"][] = $this->montharr[$firstDayInMonth->format("m") - 1] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")";
                $resultData["guests"][] = $totalGuests;
                $resultData["vews"][] = $totalVews;
                $resultData["visits"][] = $totalVisits;
                $firstDayInMonth = $date;
                $totalGuests = 0;
                $totalVews = 0;
                $totalVisits = 0;
            }
            
            
            
            $metricVals = $data->metrics;
            
            $totalGuests += $metricVals["1"];
            $totalVews += $metricVals["2"];
            $totalVisits += $metricVals["0"];
            
            
        }
        
        return $resultData;
    }

    public function getNoteText($request){
        $project = Project::find($request['id']);
        $output = "";
        if($project->note->text){
            $text = preg_split("/\r\n|\n|\r/", $project->note->text);
            foreach ($text as $paragraph) {
                $output .= view('reports.xml.listRow', ["val" => $paragraph])->render();
            }
        }


        return empty($output)? '' : view('reports.xml.paragraph', ["val" => __('text.note_title')])->render()
            . $output . view('reports.xml.paragraph', ["val" => ""])->render();

    }
}

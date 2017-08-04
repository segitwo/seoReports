<?php

namespace App\Stats;

use Carbon\Carbon;

class AutoText {

    private $today;
    private $prevDay;

    public function __construct(Carbon $today){
        $this->today = $today;
        $this->prevDay = clone $today;
        $this->prevDay->modify('-1 month');
    }
    
    public function getAutoText($work = ""){

        $textPlaceholders = [
            'prevDay' => $this->prevDay->format('d.m.Y'),
            'today' => $this->today->format('d.m.Y'),
        ];


        $textPlaceholders['dop'] = 0;
        if ($work == "") {
            $period = $_POST['period'];
        } else if($work == "dop"){
            $period = $_POST['dop_work'];
            $textPlaceholders['dop'] = 1;
        } else {
            return;
        }

        if($period != 0){
            switch($period){
                case 1:
                    if(isset($_POST['work'])){
                        switch($_POST['work']){
                            case 'Рерайт':
                                $textPlaceholders['work'] = 'добавлены необходимые вхождения в тексты на страницах:';
                                break;
                            default:
                                $textPlaceholders['work'] = 'написаны и размещены уникальные тексты на страницах:';
                                break;
                        }
                    }
                    
                    if(isset($_POST['write_text'])){
                        $support_text_rows = explode("\n", $_POST['write_text']);
                        $worklist = "";
                        foreach($support_text_rows as $row){
                            $worklist .= view('reports.xml.listRow', ["val" => $row])->render();
                        }
                        $textPlaceholders['worklist'] = $worklist;
                    }
                    
                    if(isset($_POST['hasPositions'])){
                        switch($_POST['hasPositions']){
                            case 'Новое продвижение':
                                $textPlaceholders['hasPositions1'] = 'проект только недавно начал продвижение';
                                $textPlaceholders['hasPositions2'] = 'вывод всех запросов в ТОП-100 с дальнейшим улучшением';
                                break;
                            case 'Есть позиции':
                                $textPlaceholders['hasPositions1'] = 'у сайта уже есть определенные позиции';
                                $textPlaceholders['hasPositions2'] = 'корректировка оптимизации посадочных страниц для улучшения видимости проекта';
                                break;
                        }
                    }
                    
                    return view('reports.xml.text.1month', $textPlaceholders)->render();
                    break;
                    
                case 2:
                    if(isset($_POST['work'])){
                        switch($_POST['work']) {
                            case 'Первичный аудит':
                                return view('reports.xml.text.2month', ['work' => 'В этот период наша работа была направлена на улучшение позиций сайта. Мы провели плановый технический аудит, который в себя включал:'])->render();
                                break;
                            case 'Вторичный аудит':
                                return view('reports.xml.text.2month', ['work' => 'В этом месяце наши работы были направлены на улучшение позиций отстающих запросов. С этой целью на сайте проводились следующие работы:'])->render();
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
                    if(isset($_POST['links'])){
                        $support_text_rows = explode("\n", $_POST['links']);
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
    
    public function getNextWorkText($work = 0){
        switch($work){
            case 2:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце мы планируем провести работы по устранению выявленных ошибок в ходе технического аудита и улучшению видимости сайта в поисковых системах по продвигаемым ключевым словам."])->render();
                break;
            case 3:
                return view('reports.xml.paragraph', ["val" => "В следующем периоде мы планируем провести полную проверку текущей индексации сайта в поисковых системах с целью выявления уязвимостей, своевременного их устранения. Данные работы положительно повлияют на скорость обновления страниц в индексе и, как следствие, обеспечат рост позиций."])->render();
                break;
            case 4:
                return view('reports.xml.paragraph', ["val" => "Каждый месяц мы продолжаем вести постоянный мониторинг сайта на наличие технических ошибок, вирусов, взломов, нарушений и сбоев со стороны хостинга, наличие дублей и ошибок сканирования. Проводится периодическая проверка позиций сайта, анализ изменений в выдаче и внесение соответствующих корректировок."])->render();

                break;
            case 5:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце мы планируем провести работы по анализу внутренней и внешней перелинковки, поиска путей улучшения показателя статического веса на продвигаемых страницах с целью роста позиций по продвигаемым ключевым словам."])->render();

                break;
            case 6:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце мы планируем провести работы по составлению анкор-листа, а также его внедрение на сайт для создания корректной внутренней перелинковки; поиск и составления white-листа качественных внешних доноров и размещение внешних естественных ссылок на сайт, что позволит увеличить релевантность продвигаемых страниц и будет способствовать улучшению уже достигнутых результатов."])->render();

                break;
            case 7:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце наша работа будет направлена на анализ текущего положения поисковых фраз в выдаче поисковых систем, корректировка необходимых заголовков, мета-тегов, проверка и, при необходимости, корректировка оптимизации текста для улучшения видимости сайта в поисковых системах по продвигаемым ключевым словам."])->render();

                break;
            case 8:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце мы планируем провести работы по устранению выявленных ошибок в ходе поискового аудита и улучшению видимости сайта в поисковых системах по продвигаемым ключевым словам."])->render();
                break;
            case 9:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце мы планируем провести работу со сниппетами (текстовыми описаниями в поисковой выдаче), для того чтобы сайт имел максимальную кликабельность (%CTR), формирование и управление микроразметкой сайта в выдаче, а также проведению дополнительных работ по улучшению отстающих запросов."])->render();
                break;
            case 10:
                return view('reports.xml.paragraph', ["val" => "В следующем периоде мы планируем проведение маркетингового аудита сайта для отслеживания тенденций поведения посетителя, поиска узких мест, развития функциональности сайта на основе этих данных, для оценки эффективности seo-кампании и выявлении проблемных мест в структуре, навигации и контенте сайта. Это очень важный этап работы с сайтом, в ходе которого мы даем рекомендации по улучшению ресурса."])->render();
                break;
            case 11:
                return view('reports.xml.paragraph', ["val" => "В следующем месяце мы планируем проведения анализа семантики, которую получает ресурс из естественной поисковой выдачи. Это позволит проанализировать эффективность продвижение по выбранным ключевым направлениям, а также даст возможность собрать дополнительную семантику для сайта с целью улучшение видимости ресурса в выдаче."])->render();
                break;
            case 12:
                return view('reports.xml.paragraph', ["val" => "В последующих месяцах мы планируем планомерное внедрение дополнительной семантики на сайт для увеличения видимости сайта по всему пулу тематических релевантных запросов. Прирост дополнительного релевантного трафика должен положительно повлиять на рост позиций сайта по продвигаемым ключевым запросам."])->render();
                break;
        }
    }
    
    public function getSupportText($support = []){
        $outputList = "";
        if(count($support)){
            $outputList = view('reports.xml.paragraph', ["val" => ""])->render() . view('reports.xml.paragraph', ["val" => "В рамках плановых работ по контент-сопровождению:"])->render();
        } else {
            return "";
        }
        
        if(in_array('Отзывы на сайте', $support) && in_array('Отзывы на стор. ресурсе', $support)){
            unset($support[array_search('Отзывы на сайте', $support)]);
            unset($support[array_search('Отзывы на стор. ресурсе', $support)]);
            $support[] = "Отзывы везде";
        }
        
        foreach($support as $supp){
            switch($supp) {
                case "Обратная связь":
                    $outputList .= view('reports.xml.listRow', ["val" => "Проверена работоспособность формы обратной связи и скорость реакции персонала на заявку с сайта;"])->render();
                    break;
                case "Новости":
                    $outputList .= view('reports.xml.listRow', ["val" => "Согласованы и размещены новости;"])->render();
                    break;
                case "Статьи":
                    $outputList .= view('reports.xml.listRow', ["val" => "Написана и размещена тематическая статья;"])->render();
                    break;
                case "Фотогалерея":
                    $outputList .= view('reports.xml.listRow', ["val" => "Размещены фотографии в фотогалерею;"])->render();
                    break;
                case "Отзывы на сайте":
                    $outputList .= view('reports.xml.listRow', ["val" => "Написаны и размещены отзывы на сайте."])->render();
                    break;
                case "Отзывы на стор. ресурсе":
                    $outputList .= view('reports.xml.listRow', ["val" => "Написаны и размещены отзывы на сторонних ресурсах."])->render();
                    break;
                case "Отзывы везде":
                    $outputList .= view('reports.xml.listRow', ["val" => "Написаны и размещены отзывы на сайте и на сторонних ресурсах."])->render();
                    break;
            }
        }
        
        if(isset($_POST["support_text"])){
            $support_text_rows = explode("\n", $_POST['support_text']);
            $worklist = "";
            foreach($support_text_rows as $row){
                $worklist .= view('reports.xml.listRow', ["val" => $row])->render();
            }
            $worklist = view('reports.xml.paragraph', ["val" => ""])->render() . view('reports.xml.paragraph', ["val" => "Список:"])->render() . $worklist;
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
}

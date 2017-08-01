<?php

namespace App\Stats;

class AutoText {
    
    protected $modx;
    
    public function __construct(){
        global $modx;
        $this->modx =& $modx;
    }
    
    public function getAutoText($work = ""){
        
        
        $textPlaceholders = [];
        $period = 0;
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
                            $worklist .= $this->modx->getChunk("xml.listRow", ["val" => $row]);
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
                    
                    return $this->modx->getChunk('xml.1Month', $textPlaceholders);
                    
                    break;
                    
                case 2:
                    if(isset($_POST['work'])){
                        switch($_POST['work']){
                            case 'Первичный аудит':
                                return $this->modx->getChunk('xml.2Month', ["work" => 'В этот период наша работа была направлена на улучшение позиций сайта. Мы провели плановый технический аудит, который в себя включал:']);
                                //$textPlaceholders['work'] = 'В этот период наша работа была направлена на улучшение позиций сайта. Мы провели плановый технический аудит, который в себя включал:';
                                break;
                            case 'Вторичный аудит':
                                return $this->modx->getChunk('xml.2Month', ["work" => 'В этом месяце наши работы были направлены на улучшение позиций отстающих запросов. С этой целью на сайте проводились следующие работы:']);
                                //$textPlaceholders['work'] = 'В этом месяце наши работы были направлены на улучшение позиций отстающих запросов. С этой целью на сайте проводились следующие работы:';
                                break;
                        }
                        
                    }
                    
                    
                    break;
                case 3:
                    return $this->modx->getChunk('xml.3Month', $textPlaceholders);
                    break;
                case 4:
                    return $this->modx->getChunk('xml.4Month', $textPlaceholders);
                    break;
                case 5:
                    return $this->modx->getChunk('xml.5Month', $textPlaceholders);
                    break;
                case 6:
                    return $this->modx->getChunk('xml.6Month', $textPlaceholders);
                    break;
                case 7:
                    if(isset($_POST['links'])){
                        $support_text_rows = explode("\n", $_POST['links']);
                        $worklist = "";
                        foreach($support_text_rows as $row){
                            $worklist .= $this->modx->getChunk("xml.listRow", ["val" => $row]);
                        }
                        $textPlaceholders['links'] = $worklist;
                    }
                    
                    return $this->modx->getChunk('xml.7Month', $textPlaceholders);
                    break;
                case 8:
                    return $this->modx->getChunk('xml.8Month', $textPlaceholders);
                    break;
                case 9:
                    return $this->modx->getChunk('xml.9Month', $textPlaceholders);
                    break;
                case 10:
                    return $this->modx->getChunk('xml.10Month', $textPlaceholders);
                    break;
                case 11:
                    return $this->modx->getChunk('xml.11Month', $textPlaceholders);
                    break;
                case 12:
                    return $this->modx->getChunk('xml.12Month', $textPlaceholders);
                    break;
            }
        }
    }
    
    public function getNextWorkText($work = 0){
        switch($work){
            case 2: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце мы планируем провести работы по устранению выявленных ошибок в ходе технического аудита и улучшению видимости сайта в поисковых системах по продвигаемым ключевым словам."]);
                break;
            case 3: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем периоде мы планируем провести полную проверку текущей индексации сайта в поисковых системах с целью выявления уязвимостей, своевременного их устранения. Данные работы положительно повлияют на скорость обновления страниц в индексе и, как следствие, обеспечат рост позиций."]);
                break;
            case 4: 
                return $this->modx->getChunk("xml.P", ["val" => "Каждый месяц мы продолжаем вести постоянный мониторинг сайта на наличие технических ошибок, вирусов, взломов, нарушений и сбоев со стороны хостинга, наличие дублей и ошибок сканирования. Проводится периодическая проверка позиций сайта, анализ изменений в выдаче и внесение соответствующих корректировок."]);
                break;
            case 5: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце мы планируем провести работы по анализу внутренней и внешней перелинковки, поиска путей улучшения показателя статического веса на продвигаемых страницах с целью роста позиций по продвигаемым ключевым словам."]);
                break;
            case 6: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце мы планируем провести работы по составлению анкор-листа, а также его внедрение на сайт для создания корректной внутренней перелинковки; поиск и составления white-листа качественных внешних доноров и размещение внешних естественных ссылок на сайт, что позволит увеличить релевантность продвигаемых страниц и будет способствовать улучшению уже достигнутых результатов."]);
                break;
            case 7: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце наша работа будет направлена на анализ текущего положения поисковых фраз в выдаче поисковых систем, корректировка необходимых заголовков, мета-тегов, проверка и, при необходимости, корректировка оптимизации текста для улучшения видимости сайта в поисковых системах по продвигаемым ключевым словам."]);
                break;
            case 8: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце мы планируем провести работы по устранению выявленных ошибок в ходе поискового аудита и улучшению видимости сайта в поисковых системах по продвигаемым ключевым словам."]);
                break;
            case 9: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце мы планируем провести работу со сниппетами (текстовыми описаниями в поисковой выдаче), для того чтобы сайт имел максимальную кликабельность (%CTR), формирование и управление микроразметкой сайта в выдаче, а также проведению дополнительных работ по улучшению отстающих запросов."]);
                break;
            case 10: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем периоде мы планируем проведение маркетингового аудита сайта для отслеживания тенденций поведения посетителя, поиска узких мест, развития функциональности сайта на основе этих данных, для оценки эффективности seo-кампании и выявлении проблемных мест в структуре, навигации и контенте сайта. Это очень важный этап работы с сайтом, в ходе которого мы даем рекомендации по улучшению ресурса."]);
                break;
            case 11: 
                return $this->modx->getChunk("xml.P", ["val" => "В следующем месяце мы планируем проведения анализа семантики, которую получает ресурс из естественной поисковой выдачи. Это позволит проанализировать эффективность продвижение по выбранным ключевым направлениям, а также даст возможность собрать дополнительную семантику для сайта с целью улучшение видимости ресурса в выдаче."]);
                break;
            case 12: 
                return $this->modx->getChunk("xml.P", ["val" => "В последующих месяцах мы планируем планомерное внедрение дополнительной семантики на сайт для увеличения видимости сайта по всему пулу тематических релевантных запросов. Прирост дополнительного релевантного трафика должен положительно повлиять на рост позиций сайта по продвигаемым ключевым запросам."]);
                break;
        }
    }
    
    public function getSupportText($support = []){
        $outputList = "";
        if(count($support)){
            $outputList = $this->modx->getChunk("xml.P", ["val" => ""]) . $this->modx->getChunk("xml.P", ["val" => "В рамках плановых работ по контент-сопровождению:"]);
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
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Проверена работоспособность формы обратной связи и скорость реакции персонала на заявку с сайта;"]);
                    break;
                case "Новости":
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Согласованы и размещены новости;"]);
                    break;
                case "Статьи":
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Написана и размещена тематическая статья;"]);
                    break;
                case "Фотогалерея":
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Размещены фотографии в фотогалерею;"]);
                    break;
                case "Отзывы на сайте":
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Написаны и размещены отзывы на сайте."]);
                    break;
                case "Отзывы на стор. ресурсе":
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Написаны и размещены отзывы на сторонних ресурсах."]);
                    break;
                case "Отзывы везде":
                    $outputList .= $this->modx->getChunk("xml.listRow", ["val" => "Написаны и размещены отзывы на сайте и на сторонних ресурсах."]);
                    break;
            }
        }
        
        if(isset($_POST["support_text"])){
            $support_text_rows = explode("\n", $_POST['support_text']);
            $worklist = "";
            foreach($support_text_rows as $row){
                $worklist .= $this->modx->getChunk("xml.listRow", ["val" => $row]);
            }
            $worklist = $this->modx->getChunk("xml.P", ["val" => ""]) . $this->modx->getChunk("xml.P", ["val" => "Список:"]) . $worklist;
        }
        
        return $outputList . $worklist . $this->modx->getChunk("xml.P", ["val" => ""]);
    }
    
    public function getGeneralStatistic($today) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/allp/YMetrika.php';
        
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

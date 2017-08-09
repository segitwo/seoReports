@php
    $statement = 'В период с ' . $prevDay . ' по ' . $today . ' наша работа была ';
    $statement .= ($dop == 1) ? 'также' : '' . ' направлена на сбор статистики по получаемому трафику из поисковых систем, а также анализ семантического ядра:';
@endphp
@include('reports.xml.paragraph', ['val' => $statement])

@include('reports.xml.listRow', ['val' => 'Анализ семантического ядра на актуальность;'])
@include('reports.xml.listRow', ['val' => 'Анализ семантического ядра на наличие пустых запросов;'])
@include('reports.xml.listRow', ['val' => 'Изучение запросов с высоким показателей % отказов;'])
@include('reports.xml.listRow', ['val' => 'Изучение страниц с высоким показателей % отказов;'])
@include('reports.xml.listRow', ['val' => 'Изучение запросов с высокой конверсией;'])
@include('reports.xml.listRow', ['val' => 'Изучение страниц с высокой конверсией;'])
@include('reports.xml.listRow', ['val' => 'Изучение запросов с низкой конверсией;'])
@include('reports.xml.listRow', ['val' => 'Изучение страниц с низкой конверсией;'])
@include('reports.xml.listRow', ['val' => 'Поиск нецелевых запросов в семантическом ядре;'])
@include('reports.xml.listRow', ['val' => 'Проверка корректности группировки в семантическом ядре.'])

@include('reports.xml.paragraph', ['val' => ''])
@include('reports.xml.paragraph', ['val' => 'Данный анализ позволит нам повысить видимость сайта в поисковых системах и привлечь больше релевантных пользователей.'])
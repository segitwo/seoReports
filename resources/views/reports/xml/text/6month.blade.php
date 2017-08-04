@php
    $statement = 'В этом периоде мы ' . ($dop == 1) ? 'также' : '' . ' проводили ' . ($dop == 1) ? 'повторный' : '' . ' анализ внутренней и внешней перелинковки:';
@endphp

@include('reports.xml.paragraph', ['val' => $statement])

@include('reports.xml.listRow', ['val' => 'Определение страниц, получающих недостаточно статического веса;'])
@include('reports.xml.listRow', ['val' => 'Найдены возможности по увеличению текущих показателей;'])
@include('reports.xml.listRow', ['val' => 'Проверка и наличие исходящих сcылок на внешние сайты;'])
@include('reports.xml.listRow', ['val' => 'Проверка страниц на дублирующиеся, циклические и битые ссылки;'])
@include('reports.xml.listRow', ['val' => 'Изучение входящих / исходящих внутренних ссылок и анкоров по матрице;'])
@include('reports.xml.listRow', ['val' => 'Анализ внутреннего анкор-листа, выявление ошибок.'])

@include('reports.xml.paragraph', ['val' => ''])
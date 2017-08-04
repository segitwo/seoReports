@php
    $statement = 'В период с ' . $prevDay . ' по ' . $today . ' мы ' . ($dop == 1) ? 'также' : '' . ' проводили ' . ($dop == 1) ? 'повторный' : '' . ' анализ текущей ссылочной массы:';
@endphp

@include('reports.xml.paragraph', ['val' => $statement])
@include('reports.xml.listRow', ['val' => 'Анализ выгрузки ссылок из Яндекс.Вебмастер'])
@include('reports.xml.listRow', ['val' => 'Получение данных о ссылках;'])
@include('reports.xml.listRow', ['val' => 'Анализ динамики изменения ссылочной массы;'])
@include('reports.xml.listRow', ['val' => 'Изучение возраста входящих ссылок;'])
@include('reports.xml.listRow', ['val' => 'Изучени естественности измеримых параметров ссылочной массы;'])
@include('reports.xml.listRow', ['val' => 'Проверка индексации страниц-доноров;'])
@include('reports.xml.listRow', ['val' => 'Анализ ссылочной массы конкурентов;'])
@include('reports.xml.listRow', ['val' => 'Сбор анкор-листа.'])

@include('reports.xml.paragraph', ['val' => ''])
@php
    if($dop == 1) {
        $dop = 'также';
    } else {
        $dop = '';
    }
@endphp

@include('reports.xml.paragraph', ['val' => 'В период с ' . $prevDay . ' по ' . $today . ' наша задача состояла ' . $dop . ' в проверке полноты индексации сайта в поисковых системах, проверка уязвимостей и путей их устранения:'])

@include('reports.xml.listRow', ['val' => 'Проверка индексации каждой страницы сайта;'])
@include('reports.xml.listRow', ['val' => 'Проверка возможности индексации всех страниц сайта при текущих инструкциях robots.txt;'])
@include('reports.xml.listRow', ['val' => 'Проверка целесообразности использования noindex, nofollow, SEOHide;'])
@include('reports.xml.listRow', ['val' => 'Поиск ошибок;'])
@include('reports.xml.listRow', ['val' => 'Поиск в индексе технических страниц;'])
@include('reports.xml.listRow', ['val' => 'Анализ динамики индексации сайта;'])
@include('reports.xml.listRow', ['val' => 'Проверка robots.txt на ошибки, на полноту данных, на наличие деректив для всех поисковых систем;'])
@include('reports.xml.listRow', ['val' => 'Проверка карты сайта на ошибки;'])
@include('reports.xml.listRow', ['val' => 'Проверка расстановки дат индексации и приоритетов в карте сайта;'])
@include('reports.xml.listRow', ['val' => 'Проверка актуальности карты сайта;'])
@include('reports.xml.listRow', ['val' => 'Поиск запрещенных к индексации страниц в карте сайта.'])

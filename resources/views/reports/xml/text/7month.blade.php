@php
    $statement = 'В этом периоде наша работа была ';
    $statement .= ($dop == 1) ? 'также' : '' . ' направлена на ';
    $statement .= ($dop == 1) ? 'проверку и корректировку' : 'формирование и внедрение';
    $statement .= ' анкор-листа на страницы ресурса:';
@endphp
@include('reports.xml.paragraph', ['val' => $statement])

@include('reports.xml.listRow', ['val' => 'Определение доли естественности анкоров;'])
@include('reports.xml.listRow', ['val' => 'Формирование анкор-листа для посадочных страниц;'])
@include('reports.xml.listRow', ['val' => 'Проверка анкор-листа на релевантность запросам;'])
@include('reports.xml.listRow', ['val' => 'Проверка анкор-листа на полноту охвата семантики;'])
@include('reports.xml.listRow', ['val' => 'Проверка анкор-листа на спамность и наличию уникальных биграмм;'])

@include('reports.xml.paragraph', ['val' => ''])

@php
    $statement = 'Мы провели ';
    $statement .= ($dop == 1) ? 'проверку и корректировку' : 'корректное внедрение';
    $statement .= ' анкор-листа на сайт для выравнивания и правильного распределения по страницам статического веса (PR). Данные изменения поисковые системы учтут спустя несколько апдейтов.';
@endphp
@include('reports.xml.paragraph', ['val' => $statement])

@php
    $statement = 'Мы ';
    $statement .= ($dop == 1) ? 'также' : '';
    $statement .= ' зарегистрировали сайт в справочниках и тематических каталогах для формирования естественного ссылочного окружения:';
@endphp
@include('reports.xml.paragraph', ['val' => $statement])

{{ $links }}
@include('reports.xml.paragraph', ['val' => ''])
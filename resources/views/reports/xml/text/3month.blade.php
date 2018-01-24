@php
    if($dop == 1) {
        $dop = 'также';
    } else {
        $dop = '';
    }
@endphp

@include('reports.xml.paragraph', ['val' => 'В текущем периоде наша работа ' . $dop . ' была направлена на устранение технических ошибок на сайте, выявленных в ходе аудита, а именно:'])

@include('reports.xml.listRow', ['val' => 'Устранены ошибки в html-коде сайта;'])
@include('reports.xml.listRow', ['val' => 'Устранены битые ссылки, корректно настроены редиректы;'])
@include('reports.xml.listRow', ['val' => 'Произведена склейка дубликатов (301 редирект) страниц со слешем / и без;'])
@include('reports.xml.listRow', ['val' => 'Устранены орфографические ошибки и опечатки в текстах, title и descriptions;'])
@include('reports.xml.listRow', ['val' => 'Часть страниц с дублированным контентом удалось уникализировать;'])
@include('reports.xml.listRow', ['val' => 'Страницы, долгое время не посещаемые роботом, отправлены на переобход.'])

@include('reports.xml.paragraph', ['val' => ''])
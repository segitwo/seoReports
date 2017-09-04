@php
    $statement = 'В период с ' . $prevDay . ' по ' . $today . ' мы занимались первичной оптимизацией проекта. Была сформирована стратегия продвижения – выбраны посадочные страницы под продвигаемые запросы, для них написаны и размещены релевантные мета-теги, ' . $work;
@endphp
@include('reports.xml.paragraph', ['val' => $statement])
{{ $worklist or '' }}
@include('reports.xml.paragraph', ['val' => ''])
@include('reports.xml.paragraph', ['val' => 'Отредактированы служебные файлы .htaccess, robots.txt.'])
@include('reports.xml.paragraph', ['val' => 'Также была проведена техническая оптимизация сайта для правильной индексации поисковыми системами — сайт добавлен в сервисы:'])

@include('reports.xml.listRow', ['val' => 'Яндекс.Вебмастер'])
@include('reports.xml.listRow', ['val' => 'Яндек.Метрика'])
@include('reports.xml.listRow', ['val' => 'Я.Справочник'])
@include('reports.xml.listRow', ['val' => 'Google Search Console'])
@include('reports.xml.listRow', ['val' => 'Google Analytics'])
@include('reports.xml.listRow', ['val' => 'Google Мой Бизнес (Google+)'])

@include('reports.xml.paragraph', ['val' => ''])
@include('reports.xml.paragraph', ['val' => 'В рамках первого месяца работы:'])

@include('reports.xml.listRow', ['val' => 'Собрана первичная информация о проекте, возрасте сайта, ключевых показателях;'])
@include('reports.xml.listRow', ['val' => 'Проведен ручной анализ сайта;'])
@include('reports.xml.listRow', ['val' => 'Проведен анализ эффективности CMS для продвижения (данные анализа переданы аккаунт-менеджеру);'])
@include('reports.xml.listRow', ['val' => 'Анализ исходных позиций сайта по семантике;'])
@include('reports.xml.listRow', ['val' => 'Анализ исходной видимости сайта.'])

@include('reports.xml.paragraph', ['val' => ''])

<w:p w:rsidR="00B35318" w:rsidRDefault="002524E9" w:rsidP="002524E9">
    <w:pPr>
        <w:rPr>
            <w:lang w:val="en-US" />
        </w:rPr>
    </w:pPr>
    <w:proofErr w:type="spellStart" />
    <w:r>
        <w:rPr>
            <w:lang w:val="en-US" />
        </w:rPr>
        <w:t>Поскольку {{ $hasPositions1 }}, основной нашей задачей сейчас является {{ $hasPositions2 }}.</w:t>
    </w:r>
    <w:proofErr w:type="spellEnd" />
</w:p>
@include('reports.xml.paragraph', ['val' => ''])
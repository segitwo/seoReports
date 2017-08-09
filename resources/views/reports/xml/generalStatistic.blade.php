@php
    $growthStatement = '';
    switch ($grouth) {
        case 'down':
            $growthStatement = 'стабилизация трафика сайта';
            break;
        case 'up':
            $growthStatement = 'хороший рост посещаемости сайта';
            break;
        case 'stable':
            $growthStatement = 'стабилизация трафика сайта';
            break;
    }

    $growthStatement = 'В период с ' . $prevDay  . ' по ' . $today . ' наблюдается ' . $growthStatement . ':';
@endphp
@include('reports.xml.paragraph', ['val' => $growthStatement])

@php
    $statement = 'Количество переходов пользователей из поисковых систем ';
    if($SEgrouth == 'up'){
        $statement .= 'увеличилось на ' . $SEpercent . '% (с ' . $prevSEGuests . ' до ' . $nextSEGuests . ' чел./мес.)';
    } else {
        $statement .= 'составило ' . $nextSEGuests . ' чел./мес.';
    }
@endphp
@include('reports.xml.listRow', ['val' => $statement])

@php
    $statement = 'Суммарное количество уникальных пользователей ';

    if($grouth == 'up'){
        $statement .= 'увеличилось на ' . $percent . '% (с '  . $prevGuests . ' до ' . $nextGuests .' чел./мес.)';
    } else {
        $statement .= 'составило ' . $nextGuests . ' чел./мес.';
    }

@endphp
@include('reports.xml.listRow', ['val' => $statement])

@php
    $statement = '';
    if($firstHalf == 1){
        $statement = $firstHalfText . 'поисковый трафик показал хороший рост в ' . $percent . '% - это стало возможным благодаря комплексной работе над оптимизацией проекта.';
    }
    $statement .= $secondMonthText;

@endphp
@include('reports.xml.paragraph', ['val' => $statement])

@php
    $statement = '';
    if($period == 2 && $grouth != 'up') {
        $statement .= 'На начальном этапе продвижения (первые 2-4 месяца) позиции и посещаемость могут колебаться достаточно сильно. Это абсолютно нормальное явление, с которым мы часто имеем дело. Это происходит из-за того, что поисковые системы оценивают сайт по очень большому количеству параметров. Мы всегда стараемся сократить данное время и улучшать результат как можно быстрее.';
    }
@endphp
@if($statement != '')
    @include('reports.xml.paragraph', ['val' => $statement])
@endif


{{-- [[$xml.P? &val=`[[+period:mod:is=`1`:then=`Сайт`:else=`На данный момент сайт`]] [[+allpPercent:ge=`0.85`:then=`стабильно находится в ТОПе`]] [[+allpPercent:le=`0.85`:and:if=`[[+allpPercent]]`:gt=`0`:then=`уже занял позиции в ТОП-10`]]
по [[+allpPercent:ge=`0.85`:then=`большинству поисковых запросов`]] [[+allpPercent:le=`0.85`:and:if=`[[+allpPercent]]`:gt=`0`:then=`запросам: [[+allpPhrases10]]`]] в поисковых системах.`]]
[[$xml.P? &val=``]] --}}

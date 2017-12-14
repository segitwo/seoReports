@php
    $statement = '';
    switch ($growth) {
        case 'down':
            $statement = 'стабилизация трафика сайта';
            break;
        case 'up':
            $statement = 'хороший рост посещаемости сайта';
            break;
        case 'stable':
            $statement = 'стабилизация трафика сайта';
            break;
    }

    $statement = 'В период с ' . $prevDay  . ' по ' . $today . ' наблюдается ' . $statement . '. ';

    $statement .= 'Количество переходов пользователей из поисковых систем ';
    if($SEgrowth == 'up' && !$new){
        $statement .= 'увеличилось на ' . $SEpercent . '% (с ' . $prevSEGuests . ' до ' . $nextSEGuests . ' чел./мес.). ';
    } else {
        $statement .= 'составило ' . $nextSEGuests . ' чел./мес. ';
    }

    $statement .= 'Суммарное количество уникальных пользователей ';

    if($growth == 'up' && !$new){
        $statement .= 'увеличилось на ' . $percent . '% (с '  . $prevGuests . ' до ' . $nextGuests .' чел./мес.)';
    } else {
        $statement .= 'составило ' . $nextGuests . ' чел./мес.';
    }
@endphp

<w:p w:rsidR="00454DDC" w:rsidRDefault="00454DDC" w:rsidP="00454DDC">
    <w:pPr>
        <w:rPr>
            <w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/>
        </w:rPr>
    </w:pPr>
</w:p>
<w:p w:rsidR="00454DDC" w:rsidRPr="00083FBA" w:rsidRDefault="00454DDC" w:rsidP="00454DDC">
    <w:pPr>
        <w:rPr>
            <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
            <w:sz w:val="18"/>
            <w:szCs w:val="18"/>
        </w:rPr>
    </w:pPr>
    <w:r w:rsidRPr="00083FBA">
        <w:rPr>
            <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
            <w:b/>
            <w:sz w:val="18"/>
            <w:szCs w:val="18"/>
        </w:rPr>
        <w:t>Комментарий:</w:t>
    </w:r>
    <w:proofErr w:type="gramEnd"/>
    <w:r w:rsidRPr="00083FBA">
        <w:rPr>
            <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans"/>
            <w:sz w:val="18"/>
            <w:szCs w:val="18"/>
        </w:rPr>
        <w:t xml:space="preserve"> {{ $statement }}</w:t>
    </w:r>
</w:p>

@php
    $statement = '';
    if($firstHalf == 1){
        $statement = $firstHalfText . 'поисковый трафик показал хороший рост в ' . $percent . '% - это стало возможным благодаря комплексной работе над оптимизацией проекта.';
    }
    $statement .= $secondMonthText;

@endphp
@if($statement != '')
    @include('reports.xml.paragraph', ['val' => $statement])
@endif

@php
    $statement = '';
    if($period == 2 && $grouth != 'up') {
        $statement .= 'На начальном этапе продвижения (первые 2-4 месяца) позиции и посещаемость могут колебаться достаточно сильно. Это абсолютно нормальное явление, с которым мы часто имеем дело. Это происходит из-за того, что поисковые системы оценивают сайт по очень большому количеству параметров. Мы всегда стараемся сократить данное время и улучшать результат как можно быстрее.';
    }
@endphp
@if($statement != '')
    @include('reports.xml.paragraph', ['val' => $statement])
@endif

<w:p w:rsidR="00330ACF" w:rsidRDefault="00330ACF" w:rsidP="00330ACF">
    <w:pPr>
        <w:pStyle w:val="2" />
        <w:shd w:val="clear" w:color="auto" w:fill="FFFFFF" />
        <w:spacing w:before="0" w:after="300" />
        <w:rPr>
            <w:rFonts w:ascii="Open Sans" w:hAnsi="Open Sans" w:cs="Open Sans" />
            <w:color w:val="333333" />
            <w:sz w:val="24" />
        </w:rPr>
    </w:pPr>
</w:p>

{{-- [[$xml.P? &val=`[[+period:mod:is=`1`:then=`Сайт`:else=`На данный момент сайт`]] [[+allpPercent:ge=`0.85`:then=`стабильно находится в ТОПе`]] [[+allpPercent:le=`0.85`:and:if=`[[+allpPercent]]`:gt=`0`:then=`уже занял позиции в ТОП-10`]]
по [[+allpPercent:ge=`0.85`:then=`большинству поисковых запросов`]] [[+allpPercent:le=`0.85`:and:if=`[[+allpPercent]]`:gt=`0`:then=`запросам: [[+allpPhrases10]]`]] в поисковых системах.`]]
[[$xml.P? &val=``]] --}}

<h4>Общая посещаемость сайта</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Показатель</th>
            @foreach ($totalVisitsData['periods'] as $period)
                <th>{{ $period }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Посетители</td>
            @foreach ($totalVisitsData['guests'] as $guest)
                <td>{{ $guest }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Просмотры</td>
            @foreach ($totalVisitsData['vews'] as $vew)
                <td>{{ $vew }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Визиты</td>
            @foreach ($totalVisitsData['visits'] as $visit)
                <td>{{ $visit }}</td>
            @endforeach
        </tr>
        </tbody>
    </table>
</div>

<h4>Посещаемость сайта из поисковых систем</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Поисковая система</th>
            @foreach($totalSEData['periods'] as $period)
                <th>{{ $period }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Яндекс</td>
            @foreach($totalSEData['yandex'] as $y)
                <td>{{ $y }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Google</td>
            @foreach($totalSEData['google'] as $g)
                <td>{{ $g }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Прочее</td>
            @foreach($totalSEData['other'] as $o)
                <td>{{ $o }}</td>
            @endforeach
        </tr>
        <tr class="success">
            <td>Итого</td>
            @foreach($totalSEData['total'] as $t)
                <td>{{ $t }}</td>
            @endforeach
        </tr>
        </tbody>
    </table>
</div>

<h4>Вовлечение, глубина просмотра</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Глубина просмотра</th>
            <th>Визиты</th>
            <th>Просмотры</th>
            <th>Отказы</th>
            <th>Время на сайте</th>
        </tr>
        </thead>
        <tbody>
        <tr class="success">
            <td>Итого и средние</td>
            @foreach($depthData['totals'] as $key => $total)
                <td>{{ $total }} @if($key ===2 ) % @endif</td>
            @endforeach
        </tr>
        @foreach($depthData['rows'] as $row)
            <tr>
                <td>{{ $row['key'] }}</td>
                <td>{{ $row['0'] }}</td>
                <td>{{ $row['1'] }}</td>
                <td>{{ $row['2'] }}%</td>
                <td>{{ $row['3'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Время на сайте</th>
            <th>Визиты</th>
            <th>Просмотры</th>
            <th>Отказы</th>
            <th>Время на сайте</th>
        </tr>
        </thead>
        <tbody>
        <tr class="success">
            <td>Итого и средние</td>
            @foreach($depthData['totals'] as $key => $total)
                <td>{{ $total }} @if($key ===2 ) % @endif</td>
            @endforeach
        </tr>
        @foreach($depthData['rows'] as $row)
            <tr>
                <td>{{ $row['key'] }}</td>
                <td>{{ $row['0'] }}</td>
                <td>{{ $row['1'] }}</td>
                <td>{{ $row['2'] }}%</td>
                <td>{{ $row['3'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<h4 class="text-center ">Выгрузить регионы</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="text-center"><label class="checkbox no-label toggle-all" for="checkbox-table-1"><input type="checkbox" value="" id="checkbox-table-1" data-toggle="checkbox" name="region[]"></label></th>
            <th>Регион</th>
            <th>Показатель %</th>
            <th>Визиты</th>
            <th>Просмотры</th>
        </tr>
        </thead>
        <tbody>
        @foreach($regionData as $row)
            <tr class="{{ $row['selected'] or '' }}">
                <td class="text-center"><label class="checkbox no-label" for="checkbox-table-{{ $row['idx'] }}"><input name="region[]" type="checkbox" {{ $row['checked'] or '' }} value="{{ $row['name'] }}" id="checkbox-table-{{ $row['idx'] }}" data-toggle="checkbox"></label></td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['percent'] }}</td>
                <td>{{ $row['0'] }}</td>
                <td>{{ $row['1'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<button class="btn btn-info btn-wide center-block"><span class="fui-document"></span>&nbsp;&nbsp;Сформировать отчет</button>
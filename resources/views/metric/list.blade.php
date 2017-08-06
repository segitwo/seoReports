@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center">Список метрик</h2>

        @if (count($list) > 0)
            <div class="table-responsive" id="sitesTable">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>URL</th>
                        <th></th>
                    </tr>
                    <ul>
                        @foreach ($list as $project)
                            @include('partials.metric_row', ['project' => $project])
                        @endforeach
                    </ul>

                    </tbody>
                </table>
            </div>
        @else
            <p>Список пуст</p>
        @endif

    </div>
@endsection
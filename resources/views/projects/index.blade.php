@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Список сайтов</h4>
        @if (Session::has('message'))
            <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
        @endif
        <a href="{{ route('oauth.make_ym_code') }}" class="btn btn-success btn-xs">Добавить аккаунт Яндекс</a>
        @if (count($projects) > 0)
            <div class="table-responsive" id="sitesTable">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>URL</th>
                        <th>Метрика</th>
                        <th class="text-right"></th>
                    </tr>
                    <ul>
                        @foreach ($projects as $project)
                            @include('partials.project_row', ['project' => $project])
                        @endforeach
                    </ul>

                    </tbody>
                </table>
            </div>
        @else
            <p>Список пуст</p>
        @endif

        <!-- Button trigger modal -->
        {{-- <a type="button" class="btn btn-primary btn-lg" href="{{ route('projects.create') }}">Добавить сайт</a> --}}
        <a type="button" class="btn btn-info btn-sm" href="{{ route('metrics') }}">Добавить сайт</a>
    </div>
@endsection
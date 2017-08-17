@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Редактировать проект</h4>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            {!! Form::model($project, ['method' => 'put', 'route' => ['projects.update', $project->id], 'class' => 'form']) !!}
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('note', 'Имя проекта') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('note', 'Регион продвижения') !!}
                    {!! Form::text('region', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('note', 'Отчетное число') !!}
                    {!! Form::number('report_day', null, ['class' => 'form-control', 'max' => '30', 'min' => '1']) !!}
                </div>

                <div class="form-group">
                    <label class="checkbox">
                        {!! Form::checkbox('auto', 1, $project->auto, ['data-toggle' => 'checkbox']) !!}
                        Автогенерация
                    </label>
                </div>

                <div class="form-group">
                    {!! Form::label('note', 'Куда сохранять') !!}
                    {!! Form::text('upload_path', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit('Обновить', ['class' => 'btn btn-info btn-sm']) !!}
                </div>
            </div>
            {{--
            <div class="col-md-6">
                {!! Form::label('note', 'Структура отчета') !!}
                <div class="mbl form-group">
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="support[]" data-toggle="checkbox" value="Общая посещаемость и поведенческие факторы">
                            Общая посещаемость и поведенческие факторы
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="support[]" data-toggle="checkbox" value="Источники трафика">
                            Источники трафика
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="support[]" data-toggle="checkbox" value="Страницы с высоким показателем отказов">
                            Страницы с высоким показателем отказов
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="support[]" data-toggle="checkbox" value="Популярные посадочные страницы из поисковых систем">
                            Популярные посадочные страницы из поисковых систем
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="support[]" data-toggle="checkbox" value="Фактические позиции сайта">
                            Фактические позиции сайта
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="support[]" data-toggle="checkbox" value="Среднии позиции">
                            Среднии позиции
                        </label>
                    </div>
                </div>
            </div>
            --}}

            {!! Form::close() !!}
        </div>
    </div>
@endsection

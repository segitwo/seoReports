@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Новый проект</h1>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            {!! Form::open(['route' => 'projects.store', 'class' => 'form']) !!}

            <div class="form-group">
                {!! Form::label('note', 'Имя проекта') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('note', 'URL проекта') !!}
                {!! Form::text('url', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('note', 'ID метрики') !!}
                {!! Form::text('metric', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('note', 'ID allpositions') !!}
                {!! Form::text('allp', null, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Создать', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
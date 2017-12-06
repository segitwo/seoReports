@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Настройки профиля</h4>
                @if (Session::has('message'))
                    @if (Session::get('status') == 1)
                        <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                    @elseif(Session::get('status') == 0)
                        <div class="alert alert-danger" role="alert">{{ Session::get('message') }}</div>
                    @endif

                @endif
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        {!! Form::open(['method' => 'post', 'route' => 'settings.update', 'class' => 'form']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('note', 'Логин SE Ranking') !!}
                        {!! Form::text('se_login', $user->profile->se_login, ['class' => 'form-control', 'placeholder' => '']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('note', 'Пароль SE Ranking') !!}
                        {!! Form::text('se_password', $user->profile->se_password, ['class' => 'form-control', 'placeholder' => '']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::submit('Сохранить', ['class' => 'btn btn-info btn-sm']) !!}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        </div>
    </div>
@endsection

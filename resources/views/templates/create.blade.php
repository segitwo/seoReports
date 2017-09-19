@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Создать шаблон</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        {!! Form::open(['route' => 'templates.store', 'class' => 'form']) !!}
        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    {!! Form::label(null, 'Название') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <h6>Доступные блоки</h6>
                <ul id="draggable">
                    @if(count($blocks) > 0)
                        @foreach($blocks as $block)
                            @include('templates.partials.template_block', ['block' => $block, 'disabled' => 'disabled',])
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="col-md-7">
                <h6>Структура отчета</h6>
                <div class="sortableHolder">
                    <ul id="sortable">

                    </ul>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit('Создать', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    {!! HTML::style('css/custom.css') !!}
    {!! HTML::script('js/templates.js') !!}
@endpush
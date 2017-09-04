@extends('layouts.app')

@section('content')
    <div class="container">
        {!! Form::open(['route' => 'templates.store', 'class' => 'form']) !!}
        <div class="row">
            <div class="col-md-6">
                <h4>Новый шаблон</h4>

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
                    <li data-name="TotalVisitsBlock">
                        <input type="hidden" name="blocks[]" value="TotalVisitsBlock" disabled="disabled">
                        <div class="templateBlock">
                            <div class="title">Общая посещаемость и поведенческие фаторы</div>
                            <div class="actions">
                                <span class="removeBlock fui-cross"></span>
                            </div>
                        </div>
                    </li>
                    <li data-name="block2">
                        <input type="hidden" name="blocks[]" value="block2" disabled="disabled">
                        <div class="templateBlock">
                            <div class="title">Источники трафика</div>
                            <div class="actions">
                                <span class="removeBlock fui-cross"></span>
                            </div>
                        </div>
                    </li>
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
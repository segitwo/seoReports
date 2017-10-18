@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Редактировать шаблон</h4>
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

            {!! Form::model($template, ['method' => 'put', 'route' => ['template.update', $template->id], 'class' => 'form']) !!}
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
                                @if($block->added)
                                    @include('templates.partials.template_block', ['block' => $block, 'disabled' => 'disabled', 'class' => 'ui-draggable-disabled'])
                                @else
                                    @include('templates.partials.template_block', ['block' => $block, 'disabled' => 'disabled'])
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="col-md-7">
                    <h6>Структура отчета</h6>
                    <div class="sortableHolder">
                        <ul id="sortable">
                            @if(count($blocks) > 0)
                                @foreach(collect($blocks)->sortBy('sortIndex') as $block)
                                    @if($block->added)
                                        @include('templates.partials.template_block', ['block' => $block])
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::submit('Обновить', ['class' => 'btn btn-info btn-sm']) !!}
                    </div>
                </div>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
@endsection


@push('scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

{!! HTML::style('css/custom.css') !!}
{!! HTML::script('js/templates.js') !!}
@endpush
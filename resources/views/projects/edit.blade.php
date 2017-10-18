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
                    {!! Form::label('note', 'Шаблон') !!}
                    {!! Form::select('template', $templates, $project->template_id, ['class' => 'form-control select select-primary', 'data-toggle' => 'select']) !!}
                </div>

            </div>
            <div class="col-md-6">


                {!! Form::label('note', 'Начало продвижения') !!}
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn" type="button"><span class="fui-calendar"></span></button>
                        </span>
                        <input type="text" name="start_date" value="{{ date('d-m-Y', strtotime($project->start_date)) }}" class="form-control timepicker-with-dropdowns datapicker" />
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('note', 'Куда сохранять') !!}
                    {!! Form::text('upload_path', null, ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>

                <div class="form-group">
                    <label class="checkbox">
                        {!! Form::checkbox('auto', 1, $project->auto, ['data-toggle' => 'checkbox']) !!}
                        Автогенерация
                    </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit('Обновить', ['class' => 'btn btn-info btn-sm']) !!}
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("select").select2({dropdownCssClass: 'dropdown-inverse'});
    </script>
@endpush
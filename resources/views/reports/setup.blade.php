@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Формирование отчета</h4>

        {!! Form::open(['route' => 'report_create', 'class' => 'form', 'id' => 'repForm', 'role' => 'form', 'target' => '_blank', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-md-6">
                <input type="hidden" name="id" value="{{ $project->id }}">
                <input type="hidden" name="siteid" value="{{ $project->metric }}">
                <input type="hidden" name="se_ranking" value="{{ $project->se_ranking }}">
                <input type="hidden" name="sitename" value="{{ $project->name }}">
                {!! Form::label('note', 'Отчетная дата') !!}
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn" type="button"><span class="fui-calendar"></span></button>
                        </span>
                        <input type="text" name="date" value="{{ Carbon\Carbon::now()->format('d-m-Y') }}" class="form-control timepicker-with-dropdowns datapicker" />
                    </div>
                </div>

                {!! Form::label('note', 'Регион продвижения') !!}
                <div class="form-group">
                    <input type="text" name="regionName" value="{{ $project->region }}" class="form-control">
                </div>

                {!! Form::label('note', 'Работы по продвижению') !!}
                <div class="mbl form-group">
                    <select name="period" data-action="{{ route('get_auto_text') }}" class="form-control select select-primary" data-toggle="select">
                        <option value="0">Больше года</option>
                        <option value="1">1 месяц</option>
                        <option value="2">2 месяц | Тех.аудит</option>
                        <option value="3">3 месяц | Работа по тех.аудиту</option>
                        <option value="4">4 месяц | Полнота индексации</option>
                        <option value="5">5 месяц | Ссылочная масса</option>
                        <option value="6">6 месяц | Изучение ссылок и PR</option>
                        <option value="7">7 месяц | Успешная индексация| Составление анкор-листа | Справочники</option>
                        <option value="8">8 месяц | Срез Я&G</option>
                        <option value="9">9 месяц | Работа по срезу Я&G</option>
                        <option value="10">10 месяц | Работа по сниппетам</option>
                        <option value="11">11 месяц | Маркетинговый аудит</option>
                        <option value="12">12 месяц | Анализ семантики</option>
                    </select>
                </div>
                <label class="checkbox">
                    <input type="checkbox" data-toggle="checkbox" name="commoninfo" value="1">
                    Общая информация
                </label>

                <div class="row">
                    <div class="center-block" id="autoTextPeriod"></div>
                </div>

                {!! Form::label('note', 'Дополнительные работы') !!}
                <div class="mbl form-group">
                    <select name="dop_work" data-action="{{ route('get_auto_text') }}" class="form-control select select-primary" data-toggle="select">
                        <option value="0">нет</option>
                        <option value="2">Технический аудит</option>
                        <option value="3">Работа по тех.аудиту</option>
                        <option value="4">Полнота индексации</option>
                        <option value="5">Ссылочная масса</option>
                        <option value="6">Изучение ссылок и PR</option>
                        <option value="7">Составление анкор-листа</option>
                        <option value="8">Срез Я&G</option>
                        <option value="9">Работа по срезу Я&G</option>
                        <option value="10">Работа по сниппетам</option>
                        <option value="11">Маркетинговый аудит</option>
                        <option value="12">Анализ семантики</option>
                    </select>
                </div>

                {!! Form::label('note', 'Работы по сопровождению') !!}
                <div class="mbl form-group">
                    <div class="form-inline">
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support[]" data-toggle="checkbox" value="Обратная связь">
                                Обратная связь
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support[]" data-toggle="checkbox" value="Новости">
                                Новости
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support[]" data-toggle="checkbox" value="Статьи">
                                Статьи
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support[]" data-toggle="checkbox" value="Фотогалерея">
                                Фотогалерея
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support[]" data-toggle="checkbox" value="Отзывы на сайте">
                                Отзывы на сайте
                            </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support[]" data-toggle="checkbox" value="Отзывы на стор. ресурсе">
                                Отзывы на стор. ресурсе
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="support_text" class="form-control"></textarea>
                </div>

                {!! Form::label('note', 'План работ на следующий месяц') !!}
                <div class="mbl form-group">
                    <select name="next_work" class="form-control select select-primary" data-toggle="select">
                        <option value="0">нет</option>
                        <option value="2">Работа по тех.аудиту</option>
                        <option value="3">Индексация</option>
                        <option value="4">Общая информация</option>
                        <option value="5">PR</option>
                        <option value="6">PR и Анкоры</option>
                        <option value="7">Срез Я&G</option>
                        <option value="8">Работа по срезу</option>
                        <option value="9">Сниппеты</option>
                        <option value="10">Маркетинговый аудит</option>
                        <option value="11">Анализ семантики</option>
                        <option value="12">Доп.семантика</option>
                    </select>
                </div>

                {{-- <button type="button" name="next" class="btn btn-primary btn-block" data-link="{{ route('generate_preview') }}" >Далее</button> --}}
                <button class="btn btn-info btn-wide center-block"><span class="fui-document"></span>Сформировать отчет</button>

            </div>
        </div>

        <div class="row">
            <div class="center-block" style="width: 50%">
                <div class="col-md-12">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="center-block" style="width: 50%" id="autoTextPeriod"></div>
        </div>

        <div id="regions" class="mtl mbl">

        </div>
        {!! Form::close() !!}
    </div>
@endsection

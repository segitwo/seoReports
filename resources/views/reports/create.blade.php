@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center">Формирование отчета</h2>

        {!! Form::open(['route' => 'report_create', 'class' => 'form', 'id' => 'repForm', 'role' => 'form', 'target' => '_blank', 'enctype' => 'multipart/form-data']) !!}
            <div class="row">
                <div class="center-block" style="width: 50%">
                    <div class="col-md-12">
                        <h4>Сайт</h4>
                        <div class="mbl form-group">
                            <select name="siteid" class="form-control select select-primary" data-toggle="select">
                                <option value="">Выберите сайт</option>
                                @foreach($projects as $project)
                                    @include('partials.project_row_option', ['project' => $project])
                                @endforeach
                            </select>
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="se_ranking" value="">
                            <input type="hidden" name="sitename" value="">
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="center-block" style="width: 50%">
                    <div class="col-md-6">
                        <h4>Отчетная дата</h4>
                        <div class="form-group">
                            <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn" type="button"><span class="fui-calendar"></span></button>
                                    </span>
                                <input type="text" name="date" class="form-control timepicker-with-dropdowns datapicker" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Прошлый апдейт</h4>
                        <div class="form-group">
                            <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn" type="button"><span class="fui-calendar"></span></button>
                                    </span>
                                <input type="text" name="lastUpdate" class="form-control timepicker-with-dropdowns datapicker" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="center-block" style="width: 50%">
                    <div class="col-md-12">
                        <h4>Работы по продвижению</h4>
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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="center-block" style="width: 50%" id="autoTextPeriod"></div>
            </div>
            <div class="row">
                <div class="center-block" style="width: 50%">
                    <div class="col-md-12">
                        <h4>Дополнительные работы</h4>
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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="center-block" style="width: 50%" id="autoTextDopWork"></div>
            </div>
            <div class="row">
                <div class="center-block" style="width: 50%">
                    <div class="col-md-12">
                        <h4>Работы по сопровождению</h4>
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
                        <!--div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="support_selfoption" data-toggle="checkbox" value="">
                                Свой вариант
                            </label>
                        </div>
                        <div class="form-group">
                            <textarea name="support_text_selfoption" class="form-control"></textarea>
                        </div-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="center-block" style="width: 50%">
                    <div class="col-md-12">
                        <h4>План работ на следующий месяц</h4>
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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mbl center-block clearfix" style="width: 50%">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h4>Регион продвижения</h4>
                            <input type="text" name="regionName" value="Тверь" class="form-control">
                        </div>
                        <button type="button" name="next" class="btn btn-primary btn-block" data-link="{{ route('generate_preview') }}" disabled="disabled">Далее</button>
                    </div>
                </div>
            </div>
            <div id="regions" class="mtl mbl">

            </div>
        {!! Form::close() !!}
    </div>
@endsection

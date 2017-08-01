<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Title</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=0, maximum-scale=1" />

        {!! HTML::style('dist/css/vendor/bootstrap.min.css') !!}

        {!! HTML::style('dist/css/flat-ui-pro.css') !!}
        {!! HTML::style('docs/assets/css/docs.css') !!}

        <!--[if lt IE 9]>
        {!! HTML::script('dist/js/vendor/html5shiv.js') !!}
        {!! HTML::script('dist/js/vendor/respond.min.js') !!}
        <![endif]-->
    </head>
    <body>

        <div class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                        </button>
                        <a class="navbar-brand" href="{{ route('reports.create') }}">seo-reports.com</a>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ route('reports.create') }}">Формирование отчета</a></li>
                            <li class="{{ Request::is('projects*') ? 'active' : '' }}"><a href="{{ route('projects.index') }}">Список сайтов</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <div class="text-center mtm" >
                                    Владислав
                                    <a href="[[~4? &service=`logout`]]" title="Выход"><i class="fa fa-fw fa-power-off fa-sign-out"></i>Выход</a>
                                </div>
                            </li>

                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
        @yield('content')

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->

        {!! HTML::script('dist/js//flat-ui-pro.js') !!}

        {!! HTML::script('docs/assets/js/prettify.js') !!}
        {!! HTML::script('docs/assets/js/application.js') !!}

        {!! HTML::script('dist/js/vendor/myValidation.js') !!}
        {!! HTML::script('dist/js/vendor/Bootstrap-Confirmation/bootstrap-confirmation.js') !!}

        @stack('scripts')

        {!! HTML::script('js/custom.js') !!}
    </body>
</html>

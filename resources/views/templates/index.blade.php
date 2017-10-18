@extends('layouts.app')
@section('content')
    <div class="container">
        <h4>Шаблоны</h4>
        @if (Session::has('message'))
            <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
        @endif

        @if($templates->count() > 0)
            <table class="table table-striped">
                <thead>
                <tr class="success">
                    <th>Название шаблона</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($templates as $template)
                    <tr>
                        <td>{{ $template->name }}</td>
                        <td class="text-right">
                            <div class="actions">
                                <a href="{{ route('template.edit', ['id' => $template->id]) }}" class="edit fui-new text-warning mrm"></a>
                                {{-- <span class="delete fui-cross text-danger"></span> --}}
                                {!! Form::open(['route' => ['template.destroy', $template->id], 'method' => 'delete', 'style' => 'display: inline-block']) !!}
                                    <button type='submit' class='delete fui-cross text-danger' role='link' title="Удалить из системы" onclick='return confirm("Вы действительно хотите удалить шаблон из системы?")'></button>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @else
            Вы пока не создали ни одного шаблона
        @endif
        <a type="button" class="btn btn-info btn-sm" href="{{ route('template.create') }}">Новый шаблон</a>
    </div>
@endsection
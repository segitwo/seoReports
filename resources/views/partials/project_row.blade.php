<tr>
    <td>{{$project->id}}</td>
    <td>{{$project->name}}</td>
    <td>{{$project->url}}</td>
    {{--<td>{{$project->metric}}</td>--}}
    <td>
        {!! Form::open(['route' => 'note_update', 'class' => 'noteForm']) !!}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$project->note->id}}">
            <textarea name="text" class="form-control">{{$project->note->text}}</textarea>
        {!! Form::close() !!}
        @php
            if(Carbon\Carbon::parse($project->note->updated_at)->diffInMonths(Carbon\Carbon::now())){
                $old = 'old';
            }
        @endphp
        <div class="noteUpdated {{$old or ''}}">{{ Carbon\Carbon::parse($project->note->updated_at)->format('d.m.Y') }}</div>
    </td>
    <td class="text-right">
        @if($project->auto)
            <span class="mrs fui-time text-info"></span>
        @endif
        @if(!empty($project->template_id))
            <a class="btn btn-info btn-xs {{$old or ''}}"  href="{{ route('report.setup',  ['project' => $project->id]) }}">Сгенерировать отчет</a>
        @else
            <span class="text-warning col-xs-offset-1">Необходимо выбрать шаблон</span>
        @endif
        <a class="text-info col-xs-offset-1" href="{{ route('projects.edit',  [$project->id]) }}" >Настроить</a>
    </td>
    {{--
    <td>
        {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
        <button type='submit' class='btn btn-link' role='link' title="Удалить из системы" onclick='return confirm("Вы действительно хотите удалить проект из системы?")'>Удалить</button>
        {!! Form::close() !!}
    </td>
    --}}
</tr>
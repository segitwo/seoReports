<tr>
    <td>{{$project->id}}</td>
    <td>{{$project->name}}</td>
    <td>{{$project->url}}</td>
    <td>{{$project->metric}}</td>
    <td class="text-right">
        <a class="btn btn-info btn-xs"  href="{{ route('report.setup',  ['project' => $project->id]) }}" >Сгенерировать отчет</a>
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
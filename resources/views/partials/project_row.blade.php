<tr>
    <td>{{$project->id}}</td>
    <td>{{$project->name}}</td>
    <td>{{$project->url}}</td>
    <td>{{$project->metric}}</td>
    <td>
        {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
        <button type="submit" class="btn btn-link" role="link">
            Удалить
        </button>
        {!! Form::close() !!}

        <a href="{{ route('projects.edit',  [$project->id]) }}" >
            Редактировать
        </a>

    </td>
</tr>
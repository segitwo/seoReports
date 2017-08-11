<tr>
    <td>{{$project['id']}}</td>
    <td>{{$project['name']}}</td>
    <td>{{$project['site']}}</td>
    <td class="text-right">
        {!! Form::open(['route' => ['projects.store']]) !!}

        {!! Form::hidden('name', $project['name']) !!}
        {!! Form::hidden('url', $project['site']) !!}
        {!! Form::hidden('metric', $project['id']) !!}
        {!! Form::hidden('se_ranking', $project['se_ranking']) !!}

        {!! Form::submit('Добавить', ['class' => 'btn btn-info btn-xs', 'role' => 'link']) !!}

        {!! Form::close() !!}
    </td>
</tr>
<tr>
    <td>{{$project['id']}}</td>
    <td>{{$project['name']}}</td>
    <td>{{$project['site']}}</td>
    <td>


        @if($project['added'])
            
        @else
            {!! Form::open(['route' => ['projects.store']]) !!}

            {!! Form::hidden('name', $project['name']) !!}
            {!! Form::hidden('url', $project['site']) !!}
            {!! Form::hidden('metric', $project['id']) !!}
            {!! Form::hidden('se_ranking', $project['se_ranking']) !!}

            {!! Form::submit('Добавить', ['class' => 'btn btn-primary', 'role' => 'link']) !!}

            {!! Form::close() !!}
        @endif

    </td>
</tr>
@extends('default')

@section('content')

@include('prob-notice')

    @if($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }} <br>
        </div>
        @endforeach
	@endif
	{!! Form::open(['route' => 'prizes.store']) !!}

		<div class="mb-3">
			{{ Form::label('title', 'Title', ['class'=>'form-label']) }}
			{{ Form::text('title', old('title'), array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('probability', 'Probability', ['class'=>'form-label']) }}
			{{ Form::number('probability', old('probability'), array('class' => 'form-control','min' => '0','max' => $remainingPercentage, 'placeholder' => '0 - '.$remainingPercentage,'step' => '0.01')) }}
		</div>


		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}


@stop

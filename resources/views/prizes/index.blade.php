@php
    $idealDataPoints =
    PrizeController::getIdealProbabilityDetails();
    $actualDataPoints =
    PrizeController::getActualProbabilityDetails();
    // $actualDataPoints =
    // PrizeController::getActualTrulyRandomProbabilityDetails();
@endphp

@extends('default')

@section('content')


    @include('prob-notice')

    @push('scripts')
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawIdealChart);
            google.charts.setOnLoadCallback(drawActualChart);

            function drawIdealChart() {

              var data = google.visualization.arrayToDataTable([
                ['Item', 'Probability'],
                @foreach ($idealDataPoints as $dataPoint)
                    ['{{$dataPoint['label']}}',{{$dataPoint['y']}}],
                @endforeach
              ]);

              var options = {
                // title: 'My Daily Activities',
                legend: { position: 'top', maxLines: 6 },
                sliceVisibilityThreshold: 0,
                pieHole: 0.4,
              };

              var chart = new google.visualization.PieChart(document.getElementById('ideal-piechart'));

              chart.draw(data, options);
            }
            function drawActualChart() {

              var data = google.visualization.arrayToDataTable([
                ['Item', 'Probability'],
                @foreach ($actualDataPoints as $dataPoint)
                    ['{{$dataPoint['label']}}',{{$dataPoint['y']}}],
                @endforeach
              ]);

              var options = {
                // title: 'My Daily Activities',
                legend: { position: 'top', maxLines: 6 },
                sliceVisibilityThreshold: 0,
                pieHole: 0.4,
              };

              var chart = new google.visualization.PieChart(document.getElementById('actual-piechart'));

              chart.draw(data, options);
            }
          </script>
    @endpush

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('prizes.create') }}" class="btn btn-info">Create</a>
                </div>
                <h1>Prizes</h1>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Probability</th>
                            <th>Awarded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prizes->sortByDesc('probability') as $prize)
                            <tr>
                                <td>{{ $prize->id }}</td>
                                <td>{{ $prize->title }}</td>
                                <td>{{ $prize->probability }}</td>
                                @if ($prize->distributedPrizes->count == '')
                                    <td>0</td>
                                @else
                                    <td>{{ $prize->distributedPrizes->count }}</td>
                                @endif
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('prizes.edit', [$prize->id]) }}" class="btn btn-primary">Edit</a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['prizes.destroy', $prize->id]]) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>Simulate</h3>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['method' => 'POST', 'route' => ['simulate']]) !!}
                        {{-- {!! Form::open(['method' => 'POST', 'route' => ['simulateRandom']]) !!} --}}
                        <div class="form-group">
                            {{-- {{ csrf_field() }} --}}
                            @csrf
                            {!! Form::label('number_of_prizes', 'Number of Prizes') !!}
                            {!! Form::number('number_of_prizes', 50, ['class' => 'form-control']) !!}
                        </div>
                        {!! Form::submit('Simulate', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>

                    <br>

                    <div class="card-body">
                        {!! Form::open(['method' => 'POST', 'route' => ['reset']]) !!}
                        {!! Form::submit('Reset', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>



    <div class="container  mb-4">
        <div class="row">
            <div class="col-md-6">
                <h2>Probability Settings</h2>
                <div id="ideal-piechart" style="height: 600px; width: 100%;"></div>
                {{-- <canvas id="probabilityChart"></canvas> --}}
            </div>
            <div class="col-md-6">
                <h2>Actual Rewards</h2>
                <div id="actual-piechart" style="height: 600px; width: 100%;"></div>
                {{-- <canvas id="awardedChart"></canvas> --}}
            </div>
        </div>
    </div>


@stop


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


@endpush

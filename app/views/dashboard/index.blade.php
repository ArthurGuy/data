@extends('layouts.main')

@section('content')


    <h1 style="text-align: center">{{ $daySummary }}</h1>


    <div style="display: flex; justify-content: center; flex-wrap: wrap; margin: 30px 0;">
        <div style="width: 400px; text-align: center;">
            <canvas id="weather-icon" width="300" height="300"></canvas>
            <h1>{{ $forecast->summary }}</h1>
            <h1>{{ $outTemperature }}°C</h1>
        </div>

        <div style="width:250px; align-self: flex-end; text-align: center;">

            <div style="margin-bottom: 50px;">
                @if ($location->home)
                    <h2 style="text-align: center"><span class="glyphicon glyphicon-home"></span> Home</h2>
                @else
                    <h2 style="text-align: center">Away</h2>
                @endif
            </div>

            <h4>In the next hour</h4>
            <canvas id="future-weather-icon" width="100" height="100"></canvas><br />
            {{ $futureForecast->summary }}
        </div>
    </div>

    <div style="display: flex; justify-content: center; flex-wrap: wrap;">
    @foreach ($rooms as $room)
        <div style="width: 300px;">

            <h2 style="text-align: center">{{ $room->name }}</h2>
            <h2 style="text-align: center">{{ $room->temperature }}°C | {{ round($room->humidity) }}%</h2>
            @if ($room->last_updated->lt(\Carbon\Carbon::now()->subHours(2)))
                <h2 style="text-align: center"><span class="glyphicon glyphicon-exclamation-sign" title="No Updates since {{ $room->last_updated }}" data-toggle="tooltip" data-placement="right" style="color:#FF7100;"></span></h2>
            @endif
        </div>
    @endforeach
    </div>



<!--
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">

        <pre>
            {{ print_r($forecast) }}
        </pre>

    </div>
-->

    <script>

        var skycons = new Skycons({"color": "black"});
        skycons.add("weather-icon", '{{ $forecast->icon }}');
        skycons.add("future-weather-icon", '{{ $futureForecast->icon }}');
        skycons.play();

    </script>

@stop
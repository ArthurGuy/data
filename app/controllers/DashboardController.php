<?php

use Forecast\Forecast;

class DashboardController extends BaseController {


    protected $layout = 'layouts.main';

    public function __construct()
    {

    }

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
        $forecast = new Forecast(getenv('FORECAST_API_KEY'));

        $location = \Location::where('name', 'Home')->first();
        $rooms = $location->rooms();

        $locationForcast = $forecast->get($location->latitude, $location->longitude);

        $outTemperature = round(($locationForcast->currently->temperature - 32) / 1.8, 1);

        if (\Carbon\Carbon::createFromTimestamp($locationForcast->hourly->data[0]->time)->lt(\Carbon\Carbon::now()->subMinutes(30))) {
            $futureForecast = $locationForcast->hourly->data[1];
        } else {
            $futureForecast = $locationForcast->hourly->data[0];
        }

        $daySummary = $locationForcast->hourly->summary;

        //return json_encode($futureForecast);
        //return json_encode($locationForcast);

        return View::make('dashboard.index')
            ->with('forecast', $locationForcast->currently)
            ->with('outTemperature', $outTemperature)
            ->with('rooms', $rooms)
            ->with('location', $location)
            ->with('futureForecast', $futureForecast)
            ->with('daySummary', $daySummary);
	}



}

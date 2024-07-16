<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getRainyDays($city)
    {
        $apiKey = env('WEATHER_API_KEY');
        $url = "http://api.weatherapi.com/v1/forecast.json?key={$apiKey}&q={$city}&days=7";

        $response = Http::get($url);
        if ($response->successful()) {
            $weatherData = $response->json();
            foreach ($weatherData['forecast']['forecastday'] as $day) {
                if(str_contains($day['day']['condition']['text'], 'rain')){
                    $rain[] = $day['date'];
                }
            }
            return $rain;
        }
    }

}

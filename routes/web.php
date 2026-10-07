<?php

use App\Mail\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mailable', function () {

  $startDate = now()->startOfWeek()->toDateString();
        $endDate = now()->endOfWeek()->toDateString();

        $response = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
            'from' => $startDate . 'T00:00:00.000Z',
            'lang' => 'ET',
            'page' => 0,
            'schoolId' => 38,
            'size' => 50,
            'studentGroups' => 'ea0550fb-8387-4aa2-880a-9abbd37a69ce',
            'thru' => $endDate . 'T23:59:59.999Z'
        ]);
        dd($response->json());

        $timetableEvents = collect($response()['content'])
        ->sortBy(['date', 'timeStart'])
        ->groupBy(function ($event) {
            return Carbon::parse($event['date'])->locale('et_EE')->dayName;
        });

  return new Timetable($timetableEvents, $startDate, $endDate);
  });
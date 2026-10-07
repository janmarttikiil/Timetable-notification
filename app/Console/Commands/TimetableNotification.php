<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Timetable;


#[Signature('app:timetable-notification')]
#[Description('Command description')]
class TimetableNotification extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $startDate = Carbon::parse(now()->startOfWeek());
        $endDate = Carbon::parse(now()->endOfWeek());

        $response = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
            'from' => $startDate->toDateString() . 'T00:00:00.000Z',
            'lang' => 'ET',
            'page' => 0,
            'schoolId' => 38,
            'size' => 50,
            'studentGroups' => 'ea0550fb-8387-4aa2-880a-9abbd37a69ce',
            'thru' => $endDate->toDateString() . 'T23:59:59.999Z',
        ])->throw();

        $timetableEvents = collect($response->json('content', []))
            ->sortBy(['date', 'timeStart'])
            ->groupBy(function ($event) {
                return Carbon::parse($event['date'])->locale('et_EE')->dayName;
            });

        Mail::to('jan-martti.kiil@ametikool.ee')->send(new Timetable($timetableEvents, $startDate, $endDate));
    }
}

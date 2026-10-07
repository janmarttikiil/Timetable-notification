<?php

use App\Console\Commands\TimetableNotification;

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('it combines repeated lesson names by day and keeps the longest time range', function () {
    $command = new TimetableNotification();

    $events = collect([
        ['date' => '2026-10-05T00:00:00Z', 'timeStart' => '08:30', 'timeEnd' => '09:15', 'nameEt' => 'Matemaatika'],
        ['date' => '2026-10-05T00:00:00Z', 'timeStart' => '09:45', 'timeEnd' => '10:30', 'nameEt' => 'Matemaatika'],
        ['date' => '2026-10-05T00:00:00Z', 'timeStart' => '10:45', 'timeEnd' => '11:30', 'nameEt' => 'Geograafia'],
    ]);

    $result = $command->combineLessonEntriesByDay($events);

    expect($result->get('Esmaspäev'))->toHaveCount(2)
        ->and($result->get('Esmaspäev')->first()['nameEt'])->toBe('Matemaatika')
        ->and($result->get('Esmaspäev')->first()['timeStart'])->toBe('08:30')
        ->and($result->get('Esmaspäev')->first()['timeEnd'])->toBe('10:30');
});

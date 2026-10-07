<x-mail::message>
# Timetable

{{ $startDate->translatedFormat('d.m.Y') }} - {{ $endDate->translatedFormat('d.m.Y') }}

@forelse ($timetableEvents as $day => $events)
## {{ $day }}

@foreach ($events as $event)
- {{ $event['timeStart'] ?? '—' }} - {{ $event['timeEnd'] ?? '—' }}
  {{ $event['nameEt'] ?? $event['nameEn'] ?? $event['nameRu'] ?? $event['subject'] ?? $event['name'] ?? 'Lesson' }}
@endforeach

@empty
No timetable entries for this period.
@endforelse

Thanks,<br>
Your timetable bot
</x-mail::message>

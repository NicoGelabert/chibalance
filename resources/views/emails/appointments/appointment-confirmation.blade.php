@component('mail::message')
# Hello {{ $appointment->first_name }},

Your booking has been successfully created! 🎉

**Service:** {{ $appointment->product->title ?? 'N/A' }}  
**Date:** {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}  
**Time:** {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}  

Thank you for trusting us! 🙌  

If you won’t be able to attend, you can cancel your booking by clicking the button below:

@component('mail::button', ['url' => url('/cancel-reservation/' . $appointment->cancel_token), 'color' => 'error'])
Cancel Booking
@endcomponent

@php
    // Generar enlace de Google Calendar
    $start = \Carbon\Carbon::parse($appointment->date.' '.$appointment->start_time)->format('Ymd\THis');
    $end = \Carbon\Carbon::parse($appointment->date.' '.$appointment->start_time)->addHour()->format('Ymd\THis');
    $title = urlencode("Appointment for {$appointment->product->title}");
    $details = urlencode($appointment->notes ?? '');
    $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$start}/{$end}&details={$details}&sf=true&output=xml";
@endphp

@component('mail::button', ['url' => $googleCalendarUrl, 'color' => 'primary'])
Add to Google Calendar
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# 🛑 Booking Cancelled

Hello **{{ $appointment->first_name }} {{ $appointment->last_name }}**,

Your booking has been successfully cancelled.

**Service:** {{ $appointment->product->title ?? 'N/A' }}  
**Date:** {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}  
**Time:** {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}  

@if($appointment->notes)
**Notes you added:**  
"{{ $appointment->notes }}"
@endif

@component('mail::button', ['url' => url('/')])
Back to Website
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent

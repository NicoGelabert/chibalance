@component('mail::message')
# 🛑 Booking Cancelled

The client **{{ $appointment->first_name }} {{ $appointment->last_name }}**  
Email: {{ $appointment->email }}  
Contact number: {{ $appointment->contact_number ?? 'No phone provided' }}

**Service:** {{ $appointment->product->title ?? 'N/A' }}  
**Date:** {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}  
**Time:** {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}  

@if($appointment->notes)
**Notes:**  
"{{ $appointment->notes }}"
@endif
@endcomponent
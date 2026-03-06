@component('mail::message')
# 🛎️ New Booking Received

You have received a new booking.

**First Name:** {{ $appointment->first_name }}  
**Last Name:** {{ $appointment->last_name }}  
**Email:** {{ $appointment->email }}  
**Contact Number:** {{ $appointment->contact_number ?? 'N/A' }}  
**Service:** {{ $appointment->product->title ?? 'N/A' }}  
**Date:** {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}  
**Start Time:** {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}  
**End Time:** {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}  
**Status:** {{ ucfirst($appointment->status) }}  

@if($appointment->notes)
**Client Notes:**  
"{{ $appointment->notes }}"
@endif

@component('mail::button', ['url' => url('/admin/appointments/'.$appointment->id)])
View in Dashboard
@endcomponent

@endcomponent

@extends('adminlte::page')
@section('title', 'Kelola Booking')
@section('content_header')
    <h1>Kalender Booking</h1>
@stop

@section('content')
<div id="calendar"
    data-events='@json($events ?? [])'></div>
@stop

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css" rel="stylesheet" />
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
    }
    .fc-daygrid-day:hover {
        background: #eaf1fb !important;
        cursor: pointer;
        transition: background 0.2s;
    }
    .fc-daygrid-day.active-date {
        background: #3498db !important;
        color: #fff !important;
        transition: background 0.2s;
    }
    .fc-event,
    .fc-daygrid-event,
    .fc-daygrid-dot-event {
        pointer-events: none !important;
    }
</style>
@stop



@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    initFullCalendarAdmin();
});
</script>
@endsection
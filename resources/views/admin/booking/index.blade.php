@extends('adminlte::page')
@section('title', 'Kelola Booking')
@section('content_header')
    <h1>Kalender Booking</h1>
@stop

@section('content')
<div id="calendar"></div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($events),
        eventClick: function(info) {
            // Contoh: redirect ke detail booking
            window.location.href = '/admin/booking/' + info.event.id;
        }
    });
    calendar.render();
});
</script>
@stop
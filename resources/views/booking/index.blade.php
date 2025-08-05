@extends('layouts.app')

@section('title', 'Jadwal Booking')

@section('content')
<div class="container" style="max-width: 900px; margin: 40px auto; text-align: center;">
    <h2 style="font-size:2.1rem;font-weight:700;color:#2563eb;display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:8px;">
        <span style="display:inline-flex;align-items:center;justify-content:center;background:#e0e7ff;color:#2563eb;border-radius:50%;width:38px;height:38px;font-size:1.5rem;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="4"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </span>
        Pilih Tanggal Booking
    </h2>
    <p style="font-size:1.08rem;color:#444;margin-bottom:18px;">
        Pilih tanggal yang tersedia di kalender.<br>
        Tanggal sebelum hari ini dinonaktifkan dan tanggal yang sudah penuh (10 kucing) akan dinonaktifkan.
    </p>

    <!-- Keterangan Kalender -->
    <div style="display:flex;justify-content:center;gap:24px;margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;min-width:140px;padding:10px 16px;background:#f7faff;border:1px solid #e0e7ff;border-radius:10px;">
            <!-- Contoh container booking -->
            <div style="background:#eaf1fb;border-radius:8px;padding:4px 8px;display:inline-block;min-width:110px;margin-bottom:4px;">
                <div style="font-weight:bold;">08:00 - 11:15</div>
                <div style="margin-top:2px;font-size:12px;display:flex;align-items:center;gap:6px;">
                    <span style="background:#3498db;color:#fff;border-radius:8px;padding:2px 8px;font-size:12px;">3 kucing</span>
                    <span style="background:#2ecc71;color:#fff;border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:16px;">&#8211;</span>
                </div>
            </div>
            <span style="font-size:13px;color:#333;">Contoh Container Booking</span>
        </div>
    </div>
    <div style="display:flex;justify-content:center;gap:24px;margin-bottom:16px;">
        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;min-width:110px;padding:10px 16px;background:#f7faff;border:1px solid #e0e7ff;border-radius:10px;">
            <span style="background:#eaf1fb;border-radius:8px;padding:4px 8px;font-weight:bold;">08:00 - 11:15</span>
            <span style="font-size:13px;color:#333;">Jam Booking</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;min-width:110px;padding:10px 16px;background:#f7faff;border:1px solid #e0e7ff;border-radius:10px;">
            <span style="background:#3498db;color:#fff;border-radius:8px;padding:2px 8px;font-size:13px;">3 kucing</span>
            <span style="font-size:13px;color:#333;">Jumlah Kucing</span>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;min-width:110px;padding:10px 16px;background:#f7faff;border:1px solid #e0e7ff;border-radius:10px;">
            <span style="background:#2ecc71;color:#fff;border-radius:8px;padding:2px 8px;font-size:13px;">Tim A</span>
            <span style="font-size:13px;color:#333;">Tim Groomer</span>
        </div>
    </div>
    <div style="margin-bottom:18px;">
        <span style="display:inline-block;background:#eaf1fb;border-radius:8px;padding:4px 12px;font-size:14px;">
            <b>Catatan:</b> Setiap <b>container</b> (seperti contoh di atas) di dalam tanggal pada kalender menandakan sudah ada yang booking di tanggal <b>dan</b> jam tersebut.
        </span>
        <span style="display:inline-block;background:#eaf1fb;border-radius:8px;padding:4px 12px;font-size:14px;">
             Tim Groomer akan dipilih oleh admin, jadi anda tidak perlu memilih tim groomer.
        </span>
    </div>
    <!-- /Keterangan Kalender -->

    <div id="calendar" class="calendar-customer"
        data-full-dates='@json($fullDates ?? [])'
        data-events='@json($events ?? [])'></div>
    <div id="calendar-time-info" style="margin-top:1rem;font-weight:bold"></div>
    <button id="btn-konfirmasi-booking" style="margin-top:1.5rem;" disabled>Konfirmasi</button>
    <a href="{{ route('user.dashboard') }}" class="btn-back" style="margin-top:1rem; display: inline-block;">Kembali ke dashboard</a>
</div>
@endsection

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

    /* Perlebar kalender di mobile */
    @media (max-width: 600px) {
        /* Container event jadi column */
        .calendar-customer .fc-daygrid-event-harness > a > div {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 2px !important;
            font-size: 8px !important;
            line-height: 1.3 !important;
            padding: 2px 4px !important;
            width: 100% !important;
        }
        /* Semua isi event jadi block */
        .calendar-customer .fc-daygrid-event-harness > a > div > *,
        .calendar-customer .fc-daygrid-event-harness > a > div > span,
        .calendar-customer .fc-daygrid-event-harness > a > div > div {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            text-align: left !important;
        }
    }

    .calendar-customer .fc-daygrid-event-harness,
    .calendar-customer .fc-daygrid-event {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Batasi lebar event agar tidak keluar dari kotak tanggal */
    .calendar-customer .fc-daygrid-event-harness,
    .calendar-customer .fc-daygrid-event {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        max-width: 100% !important;
        overflow: hidden !important;
        padding: 0 !important;
    }

    /* Batasi lebar container custom event */
        .calendar-customer .fc-daygrid-event-harness > a > div {
        max-width: 100%;
        box-sizing: border-box;
        word-break: break-word;
        font-size: 12px; /* font lebih kecil */
        line-height: 1.2;
        overflow: hidden;
        padding: 2px 4px;
    }
    .calendar-customer .fc-daygrid-event-harness,
    .calendar-customer .fc-daygrid-event {
        max-width: 100% !important;
        overflow: hidden !important;
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

         /* Default: font-size normal (misal 13px) untuk PC/tablet */
    #calendar-customer .fc-daygrid-event-harness > a > div,
    #calendar-customer .fc-daygrid-event-harness > a > div *,
    #calendar-customer .fc-daygrid-event-harness > a > div span {
        font-size: 13px !important;
        line-height: 1.2 !important;
    }

    /* Untuk layar <= 600px (mobile), font-size jadi kecil */
    @media (max-width: 600px) {
        #calendar .fc-daygrid-event-harness > a > div,
        #calendar .fc-daygrid-event-harness > a > div *,
        #calendar .fc-daygrid-event-harness > a > div span {
            font-size: 8px !important;
            line-height: 1.3 !important;
        }
    }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>

@endsection
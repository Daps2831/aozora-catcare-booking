@extends('adminlte::page')
@section('title', 'Kelola Booking')
@section('content_header')
    <h1>Kalender Booking</h1>
@stop

@section('content')

<!-- Mobile Calendar Controls -->
<div class="d-block d-md-none mb-2">
    <div class="card">
        <div class="card-body p-2">
            <small class="text-muted">
                Ketuk tanggal untuk melihat detail booking • Geser horizontal untuk melihat seluruh kalender
            </small>
        </div>
    </div>
</div>

<!-- Calendar Container with Mobile Wrapper -->
<div class="calendar-container">
    <div id="calendar" data-events='@json($events ?? [])'></div>
</div>

<!-- Fitur Pencarian & Filter -->
<form method="GET" class="mb-3" action="{{ route('admin.bookings') }}"> <!-- CHANGE mb-4 to mb-3 -->
    <div class="row">
        <div class="col-md-3 mb-2">
            <input type="text" name="q" class="form-control" placeholder="Cari customer, kucing, layanan..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2 mb-2">
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2 mb-2">
            <a href="{{ route('admin.bookings') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2 d-flex align-items-center">
            <input type="checkbox" name="filter_calendar" id="filter_calendar" value="1" {{ request('filter_calendar') ? 'checked' : '' }}>
            <label for="filter_calendar" class="ms-2 mb-0">Terapkan filter ke kalender</label>
        </div>
    </div>
</form>

<h3>Daftar Booking</h3>
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <form method="GET" action="{{ route('admin.bookings') }}" class="d-inline">
            @foreach(request()->except('per_page') as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <span class="me-2">Tampilkan Baris Booking per Halaman</span>
            <select name="per_page" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>
    <div>
        {{ $bookings->links() }}
    </div>
</div>

<!-- TABLE WITH RESPONSIVE WRAPPER -->
<div class="table-responsive-wrapper">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Kontak</th>
                <th>Kucing</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Tim</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $b)
            <tr class="{{ $b->statusBooking === 'Batal' ? 'booking-row-batal' : '' }}">
                <td>{{ $b->id }}</td>
                <td>{{ $b->customer->name ?? '-' }}</td>
                <td>{{ $b->customer->kontak ?? '-' }}</td>
                <td>{{ $b->kucings->pluck('nama_kucing')->join(', ') }}</td>
                <td>
                    @foreach($b->kucings as $kucing)
                        @php
                            $layananNama = '-';
                            if ($kucing->pivot->layanan_id) {
                                $layananModel = \App\Models\Layanan::find($kucing->pivot->layanan_id);
                                if ($layananModel) {
                                    $layananNama = $layananModel->nama_layanan;
                                }
                            }
                        @endphp
                        {{ $layananNama }}
                        @if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>{{ $b->tanggalBooking }}</td>
                <td>
                    @php
                        $start = \Carbon\Carbon::parse($b->tanggalBooking . ' ' . $b->jamBooking);
                        $end = $start->copy()->addMinutes($b->estimasi ?? 90);
                    @endphp
                    {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                </td>
                <td>
                    @php
                        $statusText = $b->statusBooking;
                        $statusClass = 'status-' . strtolower($statusText);
                    @endphp
                    <span class="{{ $statusClass }}">
                        @if(strtolower($statusText) === 'batal')
                            ✗ {{ $statusText }}
                        @elseif(strtolower($statusText) === 'selesai') 
                            ✓ {{ $statusText }}
                        @elseif(strtolower($statusText) === 'pending')
                            ⏳ {{ $statusText }}
                        @elseif(strtolower($statusText) === 'proses')
                            🔄 {{ $statusText }}
                        @else
                            {{ $statusText }}
                        @endif
                    </span>
                </td>
                <td>{{ $b->tim ? $b->tim->nama_tim : '-' }}</td>
                <td>
                    @if($b->statusBooking == 'Pending')
                        <a href="{{ route('admin.booking.acc', $b->id) }}" class="btn btn-success btn-sm">ACC</a>
                        <a href="{{ route('admin.booking.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @elseif($b->statusBooking == 'Proses')
                        <form action="{{ route('admin.booking.selesai', $b->id) }}" method="GET" style="display:inline;">
                            <button class="btn btn-success btn-sm" type="submit">Selesai</button>
                        </form>
                        <form action="{{ route('admin.booking.batalAcc', $b->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Batalkan ACC dan kembalikan ke pending?')">Batal ACC</button>
                        </form>
                    @elseif($b->statusBooking == 'Batal')
                        <span class="text-muted small">Booking dibatalkan</span>
                    @endif
                    <a href="{{ route('admin.booking.show', $b->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    @if($b->statusBooking !== 'Selesai')
                        <form action="{{ route('admin.booking.destroy', $b->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada booking.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $bookings->links() }}
@stop



@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.css" rel="stylesheet" />
<style>
    /* ============================================= */
    /* BASIC CONTAINER - CLEAN */
    /* ============================================= */
    .content-wrapper {
        overflow-x: hidden !important;
    }
    
    .calendar-container {
        width: 100%;
        margin: 0 auto 20px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
        border: 1px solid #dee2e6;
    }

    /* ============================================= */
    /* BASIC CALENDAR STYLES */
    /* ============================================= */
    #calendar {
        width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 15px;
    }
    
    #calendar .fc-toolbar {
        margin-bottom: 15px;
        padding: 10px 0;
    }
    
    #calendar .fc-toolbar-title {
        color: #495057;
        font-weight: 600;
    }
    
    #calendar .fc-button {
        background: #007bff;
        border: 1px solid #007bff;
        border-radius: 4px;
        padding: 6px 12px;
        color: #fff;
    }
    
    #calendar .fc-button:hover {
        background: #0056b3;
        border-color: #0056b3;
    }
    
    #calendar .fc-daygrid-day {
        border: 1px solid #dee2e6;
        background: #fff;
        min-height: 100px;
        cursor: pointer;
    }
    
    #calendar .fc-daygrid-day:hover {
        background: #f8f9fa !important;
    }
    
    #calendar .fc-daygrid-day.fc-day-today {
        background: #fff3cd !important;
    }
    
    #calendar .fc-daygrid-day-number {
        color: #495057;
        font-weight: 600;
        padding: 5px;
    }
    
    #calendar .fc-col-header-cell {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 8px 4px;
        text-align: center;
    }

    /* ============================================= */
    /* EVENT STYLING - SIMPLE */
    /* ============================================= */
    #calendar .fc-daygrid-event-harness,
    #calendar .fc-daygrid-event {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    #calendar .fc-daygrid-event-harness > a {
        background: none !important;
        border: none !important;
        padding: 0 !important;
        text-decoration: none !important;
        display: block !important;
    }

    #calendar .fc-daygrid-event-harness > a > div {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    /* ============================================= */
    /* MOBILE RESPONSIVE - FIXED SCROLL TO SUNDAY */
    /* ============================================= */
    @media (max-width: 768px) {
        .calendar-container {
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch;
            padding-right: 0 !important;
            margin-right: 0 !important;
        }
        
        #calendar {
            min-width: 1200px !important; /* INCREASE dari 900px */
            width: 1200px !important; /* FORCE exact width */
            zoom: 1.1;
            padding: 10px;
            padding-right: 0 !important;
        }
        
        #calendar .fc-scrollgrid {
            min-width: 1200px !important;
            width: 1200px !important; /* FORCE exact width */
            table-layout: fixed !important; /* PREVENT column shrinking */
        }
        
        #calendar .fc-scrollgrid-sync-table {
            min-width: 1200px !important;
            width: 1200px !important; /* FORCE exact width */
            table-layout: fixed !important;
        }
        
        #calendar .fc-daygrid-day {
            min-height: 80px;
            min-width: 165px !important; /* INCREASE dari 120px */
            max-width: 165px !important; /* PREVENT shrinking */
            width: calc(100% / 7) !important; /* FORCE equal 7 columns */
            box-sizing: border-box !important;
        }
        
        #calendar .fc-col-header-cell {
            min-width: 165px !important;
            max-width: 165px !important;
            width: calc(100% / 7) !important; /* FORCE equal 7 columns */
            box-sizing: border-box !important;
        }
        
        .fc-toolbar {
            flex-direction: column !important;
            align-items: center !important;
            gap: 8px !important;
        }
        
        .fc-toolbar-title {
            font-size: 1.2em !important;
            order: -1;
        }
        
        .fc-button {
            padding: 0.3em 0.6em !important;
            font-size: 0.8em !important;
        }
    }

    @media (max-width: 480px) {
        #calendar {
            min-width: 1400px !important; /* INCREASE dari 1000px */
            width: 1400px !important; /* FORCE exact width */
            zoom: 1.2;
            padding: 8px;
            padding-right: 0 !important;
        }
        
        #calendar .fc-scrollgrid {
            min-width: 1400px !important;
            width: 1400px !important; /* FORCE exact width */
            table-layout: fixed !important;
        }
        
        #calendar .fc-scrollgrid-sync-table {
            min-width: 1400px !important;
            width: 1400px !important; /* FORCE exact width */
            table-layout: fixed !important;
        }
        
        #calendar .fc-daygrid-day {
            min-height: 70px;
            min-width: 195px !important; /* INCREASE dari 140px */
            max-width: 195px !important; /* PREVENT shrinking */
            width: calc(100% / 7) !important; /* FORCE equal 7 columns */
            box-sizing: border-box !important;
        }
        
        #calendar .fc-col-header-cell {
            min-width: 195px !important;
            max-width: 195px !important;
            width: calc(100% / 7) !important; /* FORCE equal 7 columns */
            box-sizing: border-box !important;
        }
        
        .fc-toolbar-title {
            font-size: 1.0em !important;
        }
        
        .fc-button {
            padding: 0.2em 0.4em !important;
            font-size: 0.7em !important;
        }
    }

    @media (max-width: 360px) {
        #calendar {
            min-width: 1600px !important; /* INCREASE dari 1100px */
            width: 1600px !important; /* FORCE exact width */
            zoom: 1.3;
            padding-right: 0 !important;
        }
        
        #calendar .fc-scrollgrid {
            min-width: 1600px !important;
            width: 1600px !important; /* FORCE exact width */
            table-layout: fixed !important;
        }
        
        #calendar .fc-scrollgrid-sync-table {
            min-width: 1600px !important;
            width: 1600px !important; /* FORCE exact width */
            table-layout: fixed !important;
        }
        
        #calendar .fc-daygrid-day {
            min-width: 220px !important; /* INCREASE dari 150px */
            max-width: 220px !important;
            width: calc(100% / 7) !important; /* FORCE equal 7 columns */
            box-sizing: border-box !important;
        }
        
        #calendar .fc-col-header-cell {
            min-width: 220px !important;
            max-width: 220px !important;
            width: calc(100% / 7) !important; /* FORCE equal 7 columns */
            box-sizing: border-box !important;
        }
    }

    /* ============================================= */
    /* FORCE FULL WIDTH TABLE LAYOUT */
    /* ============================================= */
    @media (max-width: 768px) {
        /* Remove any constraints that prevent full width */
        .calendar-container,
        #calendar,
        #calendar .fc-scrollgrid,
        #calendar .fc-scrollgrid-sync-table,
        #calendar .fc-daygrid-body,
        #calendar table {
            max-width: none !important;
            margin-right: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Force table to use full allocated width */
        #calendar table {
            table-layout: fixed !important;
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }
        
        /* Ensure all day cells and headers take equal space */
        #calendar .fc-daygrid-day,
        #calendar .fc-col-header-cell {
            flex: 1 1 auto !important;
            box-sizing: border-box !important;
        }
        
        /* Prevent any element from shrinking the calendar width */
        #calendar .fc-daygrid-body,
        #calendar .fc-scrollgrid-sync-inner {
            width: 100% !important;
            min-width: 100% !important;
        }
    }


    /* ============================================= */
    /* STATUS STYLES */
    /* ============================================= */
    .status-pending {
        background: #fff3cd;
        color: #856404;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #ffc107;
    }

    .status-proses {
        background: #d1ecf1;
        color: #0c5460;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #17a2b8;
    }

    .status-selesai {
        background: #d4edda;
        color: #155724;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #28a745;
    }

    .status-batal {
        background: #f8d7da;
        color: #721c24;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
        border: 1px solid #dc3545;
        text-decoration: line-through;
    }

    .booking-row-batal {
        background: rgba(248, 215, 218, 0.3);
        opacity: 0.8;
    }

    .booking-row-batal td {
        color: #721c24;
    }

    /* ============================================= */
    /* TABLE RESPONSIVE */
    /* ============================================= */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }

    .table-responsive-wrapper::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive-wrapper::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .table-responsive-wrapper .table {
        margin-bottom: 0;
    }

    .table-responsive-wrapper .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-responsive-wrapper .table tbody tr:hover {
        background: #f8f9fa;
    }

    /* ============================================= */
    /* FORM CONTROLS */
    /* ============================================= */
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .btn-primary {
        background: #007bff;
        border: 1px solid #007bff;
    }

    .btn-primary:hover {
        background: #0056b3;
        border-color: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
        border: 1px solid #6c757d;
    }

    .btn-secondary:hover {
        background: #545b62;
        border-color: #545b62;
    }

    /* ============================================= */
    /* SPACING */
    /* ============================================= */
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-2 {
        margin-bottom: 0.75rem !important;
    }

    .d-block.d-md-none .card {
        margin-bottom: 15px !important;
        border: 1px solid #dee2e6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    h3 {
        margin-bottom: 1rem !important;
        color: #495057;
        font-weight: 600;
    }

    /* ============================================= */
    /* PRINT STYLES */
    /* ============================================= */
    @media print {
        .calendar-container,
        .table-responsive-wrapper {
            overflow: visible !important;
            box-shadow: none !important;
        }
        
        #calendar {
            min-width: auto !important;
            zoom: 1 !important;
        }
    }
</style>
@stop



@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.5/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('Calendar element not found!');
        return;
    }

    // GET EVENTS DATA
    let events = [];
    const eventsData = calendarEl.dataset.events;
    
    if (eventsData) {
        try {
            const parsedEvents = JSON.parse(eventsData);
            if (Array.isArray(parsedEvents)) {
                events = parsedEvents;
                console.log('Events loaded:', events.length);
            }
        } catch (e) {
            console.error('JSON parse error:', e);
        }
    }

    // MOBILE DETECTION
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 480;

    // CREATE CALENDAR
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        events: events,
        height: isMobile ? 'auto' : 600,
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        
        buttonText: {
            today: 'Hari ini'
        },
        
        dayMaxEvents: isSmallMobile ? 2 : 3,
        moreLinkClick: 'popover',
        eventDisplay: 'block',
        fixedWeekCount: false,
        dayHeaderFormat: isMobile ? { weekday: 'narrow' } : { weekday: 'short' },
        
        // SIMPLE EVENT CONTENT
        eventContent: function(arg) {
            const event = arg.event;
            const props = event.extendedProps || {};
            
            const jumlahKucing = props.jumlahKucing || '1';
            const namaTim = props.namaTim || 'Tim';
            const statusBooking = props.statusBooking || 'pending';

            // SIMPLE STATUS COLORS
            let bgColor = '#007bff';
            let textColor = '#fff';
            let statusIcon = '';
            
            const status = statusBooking.toLowerCase();
            if (status === 'selesai') {
                bgColor = '#28a745';
                statusIcon = '✓';
            } else if (status === 'batal') {
                bgColor = '#dc3545';
                statusIcon = '✗';
            } else if (status === 'pending') {
                bgColor = '#ffc107';
                textColor = '#856404';
                statusIcon = '⏳';
            } else if (status === 'proses') {
                bgColor = '#17a2b8';
                statusIcon = '🔄';
            }

            // MOBILE CONTENT
            if (isMobile) {
                return {
                    html: `
                        <div style="
                            background: ${bgColor};
                            color: ${textColor};
                            border-radius: 3px;
                            padding: 2px 4px;
                            font-size: 10px;
                            line-height: 1.1;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            white-space: nowrap;
                            font-weight: 500;
                            margin-bottom: 1px;
                        ">
                            ${statusIcon} ${event.title}
                        </div>
                    `
                };
            }

            // DESKTOP CONTENT
            return {
                html: `
                    <div style="
                        background: ${bgColor};
                        color: ${textColor};
                        border-radius: 4px;
                        padding: 4px 6px;
                        margin-bottom: 2px;
                        font-size: 12px;
                        line-height: 1.2;
                        overflow: hidden;
                    ">
                        <div style="font-weight: 600; margin-bottom: 1px; font-size: 15px;">
                            ${statusIcon} ${event.title}
                        </div>
                        <div style="font-size: 13px; opacity: 0.9;">
                            🐱 ${jumlahKucing} • 👥 ${namaTim}
                        </div>
                    </div>
                `
            };
        },
        
        // DATE CLICK - GO TO DATE DETAIL
        dateClick: function(info) {
            window.location.href = `/admin/booking/by-date/${info.dateStr}`;
        }
    });
    
    // RENDER CALENDAR
    calendar.render();
    
    // MOBILE SCROLL SETUP
    if (isMobile) {
        setTimeout(() => {
            const container = document.querySelector('.calendar-container');
            if (container && container.scrollWidth > container.offsetWidth) {
                // Show scroll hint
                container.scrollLeft = 20;
                setTimeout(() => {
                    container.scrollLeft = 0;
                }, 1000);
            }
        }, 500);
    }
});
</script>
@endsection
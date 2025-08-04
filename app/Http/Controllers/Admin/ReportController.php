<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimGroomer;
use App\Models\Groomer;
use App\Models\Booking;
use App\Models\Customer;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Statistik Singkat
        $jumlahTim = TimGroomer::count();
        $jumlahGroomer = Groomer::count();
        $jumlahBooking = Booking::count();
        $jumlahPelanggan = Customer::count();

        // Laporan Tim Grooming
        $timList = TimGroomer::with(['anggota1', 'anggota2', 'bookings'])->get();

        // Laporan Groomer
       $groomerList = Groomer::all();

        // Laporan Order/Transaksi
        $query = Booking::with(['customer', 'tim']);
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggalBooking', $request->tanggal);
        }
        if ($request->filled('status')) {
            $query->where('statusBooking', $request->status);
        }
        if ($request->filled('tim_id')) {
            $query->where('tim_id', $request->tim_id);
        }
        $orderList = $query->orderByDesc('tanggalBooking')->paginate(20);

        // Grafik Order Grooming (jumlah order per bulan)
        $grafikOrder = Booking::selectRaw('MONTH(tanggalBooking) as bulan, COUNT(*) as total')
            ->whereYear('tanggalBooking', Carbon::now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray(); // Tambahkan ini

        $grafikLabels = [];
        foreach (array_keys($grafikOrder) as $bulan) {
            $grafikLabels[] = date('M', mktime(0,0,0,$bulan,1));
        }

        return view('admin.reports.index', compact(
            'jumlahTim', 'jumlahGroomer', 'jumlahBooking', 'jumlahPelanggan',
            'timList', 'groomerList', 'orderList', 'grafikOrder', 'grafikLabels'
        ));
    }

    // Untuk export, bisa tambahkan method exportExcel/exportPDF sesuai kebutuhan
}
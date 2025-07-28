<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Anda bisa menambahkan logic laporan di sini
        return view('admin.reports.index');
    }
}

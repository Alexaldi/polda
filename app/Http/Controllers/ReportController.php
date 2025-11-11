<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
     public function show($id)
    {
        $report = [
            'id' => $id,
            'title' => 'Laporan Pencurian Motor',
            'status' => 'PEMERIKSAAN',
            'category' => 'Kriminal',
            'address' => 'Jl. Merdeka No.45',
            'description' => 'Laporan dugaan pencurian kendaraan bermotor pada 10 November 2025.'
        ];

        return view('pages.reports.detail', compact('report'));
    }
}

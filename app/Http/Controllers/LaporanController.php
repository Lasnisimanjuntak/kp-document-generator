<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::all();
        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'test_number' => 'required|string',
            'virtual_ccu' => 'required|integer',
            'test_time' => 'required|integer',
            'success_rate' => 'required|numeric',
            'error_rate' => 'required|numeric',
            'max_tps' => 'required|integer',
            'total_request' => 'required|integer',
            'http_codes' => 'array',
            'total_errors' => 'array',
            'labels' => 'array',
            'values' => 'array',
        ]);

        // Calculate request_per_minute
        $data['request_per_minute'] = intval($data['total_request'] / ($data['test_time'] / 60));

        Laporan::create($data);

        return redirect()->route('laporan.index');
    }

    public function edit(Laporan $laporan)
    {
        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'test_number' => 'required|string',
            'virtual_ccu' => 'required|integer',
            'test_time' => 'required|integer',
            'success_rate' => 'required|numeric',
            'error_rate' => 'required|numeric',
            'max_tps' => 'required|integer',
            'total_request' => 'required|integer',
            'http_codes' => 'array',
            'total_errors' => 'array',
            'labels' => 'array',
            'values' => 'array',
        ]);

        $data['request_per_minute'] = intval($data['total_request'] / ($data['test_time'] / 60));

        $laporan->update($data);

        return redirect()->route('laporan.index');
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return redirect()->route('laporan.index');
    }

    public function destroyAll()
    {
        Laporan::truncate();
        return redirect()->route('laporan.index')->with('success', 'Semua laporan berhasil dihapus.');
    }

    public function exportPdf()
    {
        $laporan = Laporan::all();
        $is_pdf = true;

        $pdf = new Dompdf();
        $pdf->loadHtml(View::make('laporan.index', compact('laporan', 'is_pdf'))->render());
        $pdf->setPaper('a3', 'landscape');
        $pdf->render();

        return $pdf->stream('laporan.pdf');
    }
}

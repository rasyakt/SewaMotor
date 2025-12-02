<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = \App\Models\Transaksi::all();
        return view('admin.crud.transaksis', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crud.transaksi_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'jumlah' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'status' => 'required|string',
            'tanggal' => 'required|date',
            'bukti_pembayaran' => 'nullable|string',
        ]);
        \App\Models\Transaksi::create($validated);
        return redirect()->route('crud-transaksis.index')->with('success', 'Transaksi berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        return view('admin.crud.transaksi_show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        return view('admin.crud.transaksi_form', compact('transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        $validated = $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'jumlah' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'status' => 'required|string',
            'tanggal' => 'required|date',
            'bukti_pembayaran' => 'nullable|string',
        ]);
        $transaksi->update($validated);
        return redirect()->route('crud-transaksis.index')->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = \App\Models\Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('crud-transaksis.index')->with('success', 'Transaksi berhasil dihapus');
    }
}

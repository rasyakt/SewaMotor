<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenyewaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyewaans = \App\Models\Penyewaan::all();
        return view('admin.crud.penyewaans', compact('penyewaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crud.penyewaan_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penyewa_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe_durasi' => 'required|string',
            'harga_total' => 'required|numeric',
            'status' => 'required|string',
        ]);
        \App\Models\Penyewaan::create($validated);
        return redirect()->route('crud-penyewaans.index')->with('success', 'Penyewaan berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penyewaan = \App\Models\Penyewaan::findOrFail($id);
        return view('admin.crud.penyewaan_show', compact('penyewaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penyewaan = \App\Models\Penyewaan::findOrFail($id);
        return view('admin.crud.penyewaan_form', compact('penyewaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penyewaan = \App\Models\Penyewaan::findOrFail($id);
        $validated = $request->validate([
            'penyewa_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe_durasi' => 'required|string',
            'harga_total' => 'required|numeric',
            'status' => 'required|string',
        ]);
        $penyewaan->update($validated);
        return redirect()->route('crud-penyewaans.index')->with('success', 'Penyewaan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penyewaan = \App\Models\Penyewaan::findOrFail($id);
        $penyewaan->delete();
        return redirect()->route('crud-penyewaans.index')->with('success', 'Penyewaan berhasil dihapus');
    }
}

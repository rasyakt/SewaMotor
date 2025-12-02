<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TarifRentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarifRentals = \App\Models\TarifRental::all();
        return view('admin.crud.tarif_rentals', compact('tarifRentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crud.tarif_rental_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tarif_harian' => 'required|numeric',
            'tarif_mingguan' => 'required|numeric',
            'tarif_bulanan' => 'required|numeric',
        ]);
        \App\Models\TarifRental::create($validated);
        return redirect()->route('crud-tarif-rentals.index')->with('success', 'Tarif berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tarif = \App\Models\TarifRental::findOrFail($id);
        return view('admin.crud.tarif_rental_show', compact('tarif'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tarif = \App\Models\TarifRental::findOrFail($id);
        return view('admin.crud.tarif_rental_form', compact('tarif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tarif = \App\Models\TarifRental::findOrFail($id);
        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tarif_harian' => 'required|numeric',
            'tarif_mingguan' => 'required|numeric',
            'tarif_bulanan' => 'required|numeric',
        ]);
        $tarif->update($validated);
        return redirect()->route('crud-tarif-rentals.index')->with('success', 'Tarif berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tarif = \App\Models\TarifRental::findOrFail($id);
        $tarif->delete();
        return redirect()->route('crud-tarif-rentals.index')->with('success', 'Tarif berhasil dihapus');
    }
}

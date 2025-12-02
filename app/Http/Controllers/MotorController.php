<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MotorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motors = \App\Models\Motor::all();
        return view('admin.crud.motors', compact('motors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crud.motor_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pemilik_id' => 'required|exists:users,id',
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|string|max:50',
            'no_plat' => 'required|string|max:20',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('motors/photos', 'public');
        }
        if ($request->hasFile('dokumen_kepemilikan')) {
            $validated['dokumen_kepemilikan'] = $request->file('dokumen_kepemilikan')->store('motors/dokumen', 'public');
        }
        \App\Models\Motor::create($validated);
        return redirect()->route('crud-motors.index')->with('success', 'Motor berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $motor = \App\Models\Motor::findOrFail($id);
        return view('admin.crud.motor_show', compact('motor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $motor = \App\Models\Motor::findOrFail($id);
        return view('admin.crud.motor_form', compact('motor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $motor = \App\Models\Motor::findOrFail($id);
        $validated = $request->validate([
            'pemilik_id' => 'required|exists:users,id',
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|string|max:50',
            'no_plat' => 'required|string|max:20',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('motors/photos', 'public');
        }
        if ($request->hasFile('dokumen_kepemilikan')) {
            $validated['dokumen_kepemilikan'] = $request->file('dokumen_kepemilikan')->store('motors/dokumen', 'public');
        }
        $motor->update($validated);
        return redirect()->route('crud-motors.index')->with('success', 'Motor berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $motor = \App\Models\Motor::findOrFail($id);
        $motor->delete();
        return redirect()->route('crud-motors.index')->with('success', 'Motor berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komplain;

class Warga_KomplainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $type_menu = 'komplain';
        $komplain = Komplain::where('nik', auth()->user()->nik)->get();
        return view('warga.komplain.index',compact('type_menu','komplain'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = 'komplain';
        $kategori = \App\Models\KategoriKomplain::all();
        return view('warga.komplain.create',compact('type_menu','kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
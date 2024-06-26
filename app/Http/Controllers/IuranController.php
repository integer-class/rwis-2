<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Alert;


class IuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $type_menu = 'iuran';


        

        $bulan = $request->input('bulan', date('m')); // Default to current month if not provided
        $tahun = $request->input('tahun', date('Y'));


        $totalPemasukan = Iuran::where('status', 'pemasukan')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('jumlah');

        $totalPengeluaran = Iuran::where('status', 'pengeluaran')
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('jumlah');


        $totalSemuaPemasukan = Iuran::where('status', 'pemasukan')->sum('jumlah');
        $totalSemuaPengeluaran = Iuran::where('status', 'pengeluaran')->sum('jumlah');

        
        $iuran = Iuran::join('kartu_keluarga', 'iuran.nomor_kk', '=', 'kartu_keluarga.nomor_kk')
            ->whereMonth('iuran.tanggal', $bulan)
            ->whereYear('iuran.tanggal', $tahun)
            ->paginate(10);
            
        
        return view ('rw.data_iuran.index',compact('bulan','tahun','type_menu','iuran','totalPemasukan','totalPengeluaran','totalSemuaPemasukan','totalSemuaPengeluaran'));
        

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = 'form_pengeluaran';

        
        return view ('rw.data_iuran.create',compact('type_menu'));

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

     
        $kk = Penduduk::where('nik', auth()->user()->nik)->first();

        DB::table('iuran')->insert([
            'nomor_kk' => $kk->nomor_kk,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' =>  $request->keterangan,
            'status' => 'pengeluaran',
            'id_rt' => auth()->user()->id_rt,
            'is_paid' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Alert::success('Berhasil!', 'Berhasil menambahkan data!');
        
        return redirect()->back(); 

        // echo $kk;
        

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

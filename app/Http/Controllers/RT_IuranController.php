<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KartuKeluarga;
use Carbon\Carbon;
use Alert;





class RT_IuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
{
    $type_menu = 'iuran';

    $id_rt = auth()->user()->id_rt;
    $bulan = $request->input('bulan', date('m')); // Default to current month if not provided
    $tahun = $request->input('tahun', date('Y')); // Default to current year if not provided

    $result = DB::table('kartu_keluarga as kk')
        ->join('penduduk as p', 'kk.nomor_kk', '=', 'p.nomor_kk')
        ->where('p.id_rt', $id_rt)
        ->select('kk.nomor_kk', 'kk.kepalakeluarga', DB::raw(
            "CASE 
                WHEN EXISTS (
                    SELECT 1
                    FROM iuran i
                    WHERE i.nomor_kk = kk.nomor_kk 
                    AND i.is_paid = TRUE 
                    AND MONTH(i.tanggal) = ? 
                    AND YEAR(i.tanggal) = ?
                ) THEN 'sudah'
                ELSE 'belum'
            END as sudah_bayar", [$bulan, $tahun]))
        ->groupBy('kk.nomor_kk')
        ->setBindings([$bulan, $tahun], 'select')
        ->paginate(10);

    return view('rt.rt_data_iuran.index', compact('type_menu', 'result', 'bulan', 'tahun'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $id_rt = auth()->user()->id_rt;


        $validatedData = $request->validate([
            'nomor_kk' => 'required|exists:kartu_keluarga,nomor_kk',
            'jumlah' => 'required|numeric',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2020|max:' . date('Y'),
        ]);

        $tanggal = Carbon::createFromDate($validatedData['tahun'], $validatedData['bulan'], 1);

        DB::table('iuran')->insert([
            'nomor_kk' => $validatedData['nomor_kk'],
            'jumlah' => $validatedData['jumlah'],
            'tanggal' => $tanggal,
            'is_paid' => true,
            'id_rt' => $id_rt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Alert::success('Berhasil!', 'Berhasil menambahkan data!');

        return redirect()->back();
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

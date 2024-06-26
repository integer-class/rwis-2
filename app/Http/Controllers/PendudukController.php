<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\User;
use Alert;
use App\Models\KartuKeluarga;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type_menu = 'penduduk'; 
        if ($request->has('search')) {
            $penduduk = \App\Models\Penduduk::join('users', 'penduduk.nik', '=', 'users.nik')
                ->join('rt', 'penduduk.id_rt', '=', 'rt.id_rt')
                ->where('penduduk.nama', 'LIKE', '%' . $request->search . '%')
                ->where('penduduk.arsip', 'false') 
                ->where('penduduk.status', 'hidup') 
                ->paginate(10);
        } else {
            $penduduk = \App\Models\Penduduk::join('users', 'penduduk.nik', '=', 'users.nik')
                ->join('rt', 'penduduk.id_rt', '=', 'rt.id_rt')
                ->where('penduduk.arsip', 'false') // Filter records where arsip is 'false'
                ->where('penduduk.status', 'hidup') 
                ->paginate(10);
        }
        return view('rw.data_penduduk.index', compact('penduduk', 'type_menu'));
    }

    public function create()
    {
        $rt = \App\Models\Rt::all();
        $kartukeluarga = \App\Models\KartuKeluarga::all();
        $type_menu = 'penduduk'; 

        return view('rw.data_penduduk.tambah_penduduk', compact('rt', 'type_menu', 'kartukeluarga'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
    $existingPenduduk = Penduduk::where('nik', $request->nik)->first();

    // Jika NIK sudah ada, tampilkan pesan kesalahan
    if($existingPenduduk){
        Alert::error('Data Duplikasi', 'NIK Telah Terdaftar!');
        return redirect()->back();
    }
    else {
        
    
        // Pisahkan nomor KK dan nama kepala keluarga
        $nomor_kk = $request->nomor_kk;
        $pieces = explode(" - ", $nomor_kk);
        $nomor_kk = $pieces[0];
    
        // Ambil nama depan dalam huruf besar
        $nama = $request->nama;
        $namaArray = explode(' ', $nama);
        $namaDepan = ucfirst($namaArray[0]);
        $namaUpper = strtoupper($namaDepan);
    
        // Buat data penduduk
        $penduduk = Penduduk::create([
            'nik' => $request->nik,
            'nomor_kk' => $nomor_kk,
            'nama' => $request->nama,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golong_darah' => $request->golongan_darah,
            'alamat' => $request->alamat,
            'agama' => $request->agama,
            'status_perkawinan' => $request->status_perkawinan,
            'pekerjaan' => $request->pekerjaan,
            'id_rt' => $request->id_rt,
            'pendapatan' => $request->pendapatan,
            'status_sosial' => $request->status_sosial,
            'status_rumah' => $request->status_rumah,
            'status_kesehatan' => $request->status_kesehatan,
            'foto' => 'default.png',
        ]);
    
        // Buat user
        $user = User::create([
            'role' => $request->roles,
            'nik' => $request->nik,
            'id_rt' => $request->id_rt,
            'password' => Hash::make($namaUpper . $request->tanggal_lahir),
            'default_password' => 'yes',
        ]);
    
        // Beri feedback berhasil
        Alert::success('Berhasil!', 'Berhasil menambahkan data!');
        
        // Redirect kembali ke halaman form
        return redirect()->back();
    }   
}
    

    public function show(Penduduk $penduduk)
    {
        $penduduk = \App\Models\Penduduk::join('users', 'penduduk.nik', '=', 'users.nik')
            ->join('rt', 'users.id_rt', '=', 'rt.id_rt')
            ->where('penduduk.nik', $penduduk->nik)
            ->first();
        $jumlah_anggota_keluarga = \App\Models\Penduduk::where('nomor_kk', $penduduk->nomor_kk)->count();
        $komplain = \App\Models\Komplain::where('nik', $penduduk->nik)->count();
        $penduduk_kk = \App\Models\Penduduk::where('nomor_kk', $penduduk->nomor_kk)
            ->where('nik', '!=', $penduduk->nik) // Exclude the person
            ->get();
        $type_menu = 'detail_penduduk'; 
        return view('rw.data_penduduk.detail_penduduk', compact('penduduk', 'type_menu', 'penduduk_kk', 'jumlah_anggota_keluarga', 'komplain'));
    }

    public function edit($nik)
    {
        $type_menu = 'penduduk'; 
        $penduduk = Penduduk::where('nik', $nik)->firstOrFail();
        $rt = Rt::all();
        $kartukeluarga = KartuKeluarga::all();
        return view('rw.data_penduduk.penduduk_edit', compact('penduduk', 'rt', 'kartukeluarga', 'type_menu'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        // $request->validate([
        //     'nama' => 'required|string|max:255',
        //     'alamat' => 'required|string|max:255',
        //     'tanggal_lahir' => 'required|date',
        //     'jenis_kelamin' => 'required|in:L,P',
        //     'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu', 
        //     'status_perkawinan' => 'required|in:Kawin,Belum Kawin,Cerai',
        //     'golongan_darah' => 'required|in:A,B,AB,O',
        //     'id_rt' => 'required|exists:rt,id_rt',
        //     'pekerjaan' => 'required|string|max:255',
        //     'nomor_kk' => 'required|string|max:255',
        //     'status' => 'required|in:hidup,meninggal,pindah',
        //     'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'status_sosial' => 'required|in:Janda,Yatim Piatu,Lainnya',
        //     'status_rumah' => 'required|in:Milik Sendiri,Sewa,Kontrak,Lainnya',
        //     'status_kesehatan' =>'required|in:Sehat,Sakit,Disabilitas',
        // ]);


        //exist nik 
        $existingPenduduk = Penduduk::where('nik', $request->nik)->first();

        if ($existingPenduduk && $existingPenduduk->nik != $penduduk->nik) {
            Alert::error('Data Duplikasi', 'NIK Telah Terdaftar!');
            return redirect()->back();
        } else {

            $penduduk->update([
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'agama' => $request->input('agama'),
                'status_perkawinan' => $request->input('status_perkawinan'),
                'golong_darah' => $request->input('golongan_darah'),
                'id_rt' => $request->input('id_rt'),
                'pekerjaan' => $request->input('pekerjaan'),
                'nomor_kk' => $request->input('nomor_kk'),
                'status' => strtolower($request->input('status')),
                'pendapatan' => $request->input('pendapatan'),
                'status_sosial' => $request->input('status_sosial'),
                'status_rumah' => $request->input('status_rumah'),
                'status_kesehatan' => $request->input('status_kesehatan'),
            ]);
    
            if ($request->hasFile('foto')) {
                $fotoName = $request->file('foto')->getClientOriginalName();
                $request->file('foto')->storeAs('fotos', $fotoName, 'public');
                $penduduk->foto = $fotoName;
                $penduduk->save();
            }
    
            
           
    
            //jika status penduduk meninggal maka user akan diaresipkan
            if($request->status == 'meninggal' || $request->status == 'pindah'){
                $penduduk->arsip = 'true';
                $penduduk->save();
            }
    
    
            return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil diperbarui.');
            
        }

       
    }


    public function arsip($id)
    {
        $penduduk = Penduduk::find($id);
    
        if (!$penduduk) {
            return redirect()->route('penduduk.index')->with('error', 'Penduduk not found');
        }    
        $penduduk->arsip = 'true'; 
        $penduduk->save();
    
        return redirect()->route('penduduk.index')->with('success', 'Penduduk has been archived successfully');
    }


    public function restore($id)
    {
        $penduduk = Penduduk::find($id);
    
        if (!$penduduk) {
            return redirect()->route('penduduk.index')->with('error', 'Penduduk not found');
        }    
        $penduduk->arsip = 'false'; 
        $penduduk->save();
    
        return redirect()->route('penduduk.index')->with('success', 'Penduduk has been archived successfully');
    }

    
}

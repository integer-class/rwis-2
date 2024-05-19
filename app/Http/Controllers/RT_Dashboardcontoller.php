<?php

namespace App\Http\Controllers;
use App\Models\penduduk;
use App\Models\Pengumuman_rt;
use App\Models\User;
use Alert;






use Illuminate\Http\Request;

class RT_Dashboardcontoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $type_menu = 'dashboard';

    $penduduk = Penduduk::where('nik', auth()->user()->nik)->first();
    if (!$penduduk) {
        return redirect()->route('error.page')->with('error', 'Data penduduk tidak ditemukan.');
    }

    $pengumuman_rt = Pengumuman_rt::where('id_rt', $penduduk->id_rt)
        ->join('pengumuman', 'pengumuman_rt.id_pengumuman', '=', 'pengumuman.id_pengumuman')
        ->select('pengumuman.*')
        ->first();

    if ($pengumuman_rt) {
        $tanggal_pengumuman = $pengumuman_rt->tanggal; 
    } 
    else {
        $tanggal_pengumuman = null;
    }
    $tanggal_sekarang = date('Y-m-d');

    $password_default = auth()->user()->default_password;
    
    return view('rt.index', compact('type_menu', 'penduduk', 'password_default', 'pengumuman_rt', 'tanggal_sekarang', 'tanggal_pengumuman'));
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
    public function update(Request $request, string $nik)
    {

        $penduduk = Penduduk::where('nik', $nik)->first();


        if ($request->has('password')) {
            $user = User::where('nik', $penduduk->nik)->first();
            $user->update([
                'password' => bcrypt($request->password),
                'default_password' => 'no', 
            ]);
        }
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('penduduk'), $imageName);
            $penduduk->update(['foto' => $imageName]);
        }

        Alert::success('Berhasil!', 'Berhasil menambahkan data!');
        
        return redirect()->back();


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

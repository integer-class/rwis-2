<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\PengumumanRt;
use App\Models\Pengumuman;
use App\Models\User;
use Alert;
use App\Models\KartuKeluarga;
use App\Models\Iuran;
use App\Models\Pengumuman_rt;
use Illuminate\Support\Facades\DB;
use DateTime;

class Warga_DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type_menu = 'dashboard';

        $penduduk_hitung = Penduduk::where('id_rt', auth()->user()->id_rt)->count();

        $penduduk = Penduduk::where('nik', auth()->user()->nik)->first();
        if (!$penduduk) {
            return redirect()->route('error.page')->with('error', 'Data penduduk tidak ditemukan.');
        }

        $jumlah_laki = Penduduk::where('jenis_kelamin', 'L')
            ->where('id_rt', auth()->user()->id_rt)
            ->count();

        $jumlah_perempuan = Penduduk::where('jenis_kelamin', 'P')
            ->where('id_rt', auth()->user()->id_rt)
            ->count();

        $kartu_keluarga = KartuKeluarga::count();
        $totalSemuaPemasukan = Iuran::where('status', 'pemasukan')->sum('jumlah');
        $totalSemuaPengeluaran = Iuran::where('status', 'pengeluaran')->sum('jumlah');
        $jumlah_kas = $totalSemuaPemasukan - $totalSemuaPengeluaran;

        $pengumuman = Pengumuman::join('penduduk', 'pengumuman.nik', '=', 'penduduk.nik')
            ->where('penduduk.id_rt', auth()->user()->id_rt)
            ->take(8)
            ->get();

        $pengumuman_rt = Pengumuman_rt::where('id_rt', $penduduk->id_rt)
            ->join('pengumuman', 'pengumuman_rt.id_pengumuman', '=', 'pengumuman.id_pengumuman')
            ->select('pengumuman.*')
            ->first();
            $pengumuman_tt = Pengumuman_rt::where('id_rt', $penduduk->id_rt)
            ->join('pengumuman', 'pengumuman_rt.id_pengumuman', '=', 'pengumuman.id_pengumuman')
            ->select('pengumuman.*')
            ->get();

        if ($pengumuman_rt) {
            $tanggal_pengumuman = $pengumuman_rt->tanggal_pengumuman; 
        } else {
            $tanggal_pengumuman = null;
        }
        $tanggal_sekarang = date('Y-m-d');

        $password_default = auth()->user()->default_password;

        $iuran_bulanan = Iuran::where('status', 'pemasukan')
            ->select(DB::raw('SUM(jumlah) as total'), DB::raw('MONTH(tanggal) as bulan'))
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->get();

        // Array nama bulan
        $nama_bulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $nama_bulan[$i] = DateTime::createFromFormat('!m', $i)->format('F');
        }

        // Hitung usia setiap penduduk
        $usia_penduduk = Penduduk::selectRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS usia')->get();

        // Kelompokkan penduduk berdasarkan usia
        $kategori_usia = [
            'Anak-anak' => 0,
            'Remaja' => 0,
            'Dewasa' => 0,
            'Lansia' => 0
        ];

        foreach ($usia_penduduk as $orang) {
            if ($orang->usia <= 12) {
                $kategori_usia['Anak-anak']++;
            } elseif ($orang->usia <= 18) {
                $kategori_usia['Remaja']++;
            } elseif ($orang->usia <= 60) {
                $kategori_usia['Dewasa']++;
            } else {
                $kategori_usia['Lansia']++;
            }
        }

        // Data untuk grafik batang
        $labels_usia = array_keys($kategori_usia);
        $data_usia = array_values($kategori_usia);

    
        return view('warga.index', compact('pengumuman_tt', 'type_menu', 'penduduk', 'pengumuman', 'penduduk_hitung', 'password_default', 'pengumuman_rt', 'tanggal_sekarang', 'tanggal_pengumuman', 'jumlah_kas', 'kartu_keluarga', 'jumlah_laki', 'jumlah_perempuan', 'iuran_bulanan', 'nama_bulan', 'labels_usia', 'data_usia'));
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
        $penduduk = \App\Models\Penduduk::join('users', 'penduduk.nik', '=', 'users.nik')
            ->join('rt', 'users.id_rt', '=', 'rt.id_rt')
            ->where('penduduk.nik', $id)
            ->first();

            if (!$penduduk) {
                return redirect()->route('error.page')->with('error', 'Data penduduk tidak ditemukan.');
            }
            
        $jumlah_anggota_keluarga = \App\Models\Penduduk::where('nomor_kk', $penduduk->nomor_kk)->count();
        $Pengumuman = \App\Models\Pengumuman::where('nik', $penduduk->nik)->count();
        $penduduk_kk = \App\Models\Penduduk::where('nomor_kk', $penduduk->nomor_kk)
            ->where('nik', '!=', $penduduk->nik) 
            ->get();
        $type_menu = 'detail_penduduk'; 
        return view('warga.show', compact('penduduk', 'type_menu', 'penduduk_kk', 'jumlah_anggota_keluarga', 'pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function profile($id)
    {
        $penduduk = \App\Models\Penduduk::join('users', 'penduduk.nik', '=', 'users.nik')
            ->join('rt', 'users.id_rt', '=', 'rt.id_rt')
            ->where('penduduk.nik', $id)
            ->first();

          
        $jumlah_anggota_keluarga = \App\Models\Penduduk::where('nomor_kk', $penduduk->nomor_kk)->count();
        $Pengumuman = \App\Models\Pengumuman::where('nik', $penduduk->nik)->count();
        $penduduk_kk = \App\Models\Penduduk::where('nomor_kk', $penduduk->nomor_kk)
            ->where('nik', '!=', $penduduk->nik) 
            ->get();
        $type_menu = 'detail_penduduk'; 
        return view('warga.profile', compact('penduduk', 'type_menu', 'penduduk_kk', 'pendudukk', 'jumlah_anggota_keluarga', 'pengumuman'));
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

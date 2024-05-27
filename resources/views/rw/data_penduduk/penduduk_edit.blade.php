@extends('layouts.app')

@section('title', 'Edit Penduduk')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Penduduk</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Data</a></div>
                    <div class="breadcrumb-item"><a href="#">Penduduk</a></div>
                    <div class="breadcrumb-item">Edit Penduduk</div>
                </div>
            </div>

            <div class="section-body">
                @include('sweetalert::alert')

                <div class="card">
                    <form action="{{ route('penduduk.update', $penduduk->nik) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Form</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama (sesuai di KTP)</label>
                                        <input type="text" class="form-control" name="nama" value="{{ old('nama', $penduduk->nama) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="number" class="form-control" name="nik" value="{{ old('nik', $penduduk->nik) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Agama</label>
                                        <select class="form-control" name="agama">
                                            <option value="Islam" {{ old('agama', $penduduk->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                            <option value="Kristen" {{ old('agama', $penduduk->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                            <option value="Katolik" {{ old('agama', $penduduk->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                            <option value="Hindu" {{ old('agama', $penduduk->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                            <option value="Budha" {{ old('agama', $penduduk->agama) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                            <option value="Konghucu" {{ old('agama', $penduduk->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status Kawin</label>
                                        <select class="form-control" name="status_perkawinan">
                                            <option value="Kawin" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                            <option value="Belum Kawin" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                            <option value="Cerai" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Golongan Darah</label>
                                        <select class="form-control" name="golongan_darah">
                                            <option value="A" {{ old('golongan_darah', $penduduk->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ old('golongan_darah', $penduduk->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                                            <option value="AB" {{ old('golongan_darah', $penduduk->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                                            <option value="O" {{ old('golongan_darah', $penduduk->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control" name="jenis_kelamin">
                                            <option value="L" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $penduduk->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>RT</label>
                                        <select class="form-control" name="id_rt">
                                            <option value="">Select RT</option>
                                            @foreach($rt as $r)
                                                <option value="{{ $r->id_rt }}" {{ old('id_rt', $penduduk->id_rt) == $r->id_rt ? 'selected' : '' }}>{{ $r->nama_rt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Pekerjaan</label>
                                        <input type="text" class="form-control" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nomor KK <span style="color:red;">(Jika tidak memiliki KK, <a href="{{ route('kartu-keluarga.create') }}">buat disini</a>)</span></label>
                                        <input type="text" class="form-control" name="nomor_kk" id="tags" value="{{ old('nomor_kk', $penduduk->nomor_kk) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea style="height: 100px" class="form-control" name="alamat">{{ old('alamat', $penduduk->alamat) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Foto</label>
                                        <input type="file" class="form-control-file" name="foto">
                                        @if ($penduduk->foto)
                                            <img src="{{ asset('storage/fotos/' . $penduduk->foto) }}" alt="Foto" class="img-thumbnail mt-2" width="150">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        var availableTags = [
            @foreach($kartukeluarga as $kk)
                {
                    label: "{{ $kk->nomor_kk }} - {{ $kk->kepalakeluarga }}",
                    value: "{{ $kk->nomor_kk }}",
                },
            @endforeach
        ];
        $("#tags").autocomplete({
            source: availableTags,
            select: function(event, ui) {
                $(this).val(ui.item.label);
                return false;
            }
        });
    });
</script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
@endpush

@extends('layouts.app')

@section('title', 'Multiple Upload')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
    href="{{ asset('library/dropzone/dist/dropzone.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <style type="text/css">
        .dz-preview .dz-image img{
          width: 100% !important;
          height: 100% !important;
          object-fit: cover;
        }
      </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah foto dokumentasi  </h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Dokumentasi</a></div>
                    <div class="breadcrumb-item">Tambah Foto</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section">
                    
                    <a style="width:130px; height:38px; margin-bottom:20px" href="{{ route('dokumentasi.index') }}" class="btn btn-lg btn-primary">Kembali</a>


                </h2>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Maximal upload foto 1mb
                                </h4>
                            </div>
                            <div class="card-body">
                                <form style="margin-bottom: 20px" action="{{ route('dokumentasi.storefoto') }}" method="post" enctype="multipart/form-data" class="dropzone">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <!-- Removed title input field -->
    
                                    <!-- Removed title input field -->
                                    
                                </form>
                                <button id="uploadFile" class="btn btn-success mt-1">Upload Images</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    {{-- <script src="{{ asset('library/dropzone/dist/min/dropzone.min.js') }}"></script> --}}
    <script src="{{ asset('library/dropzone/dist/min/dropzone.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-multiple-upload.js') }}"></script>
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script type="text/javascript">
  
        Dropzone.autoDiscover = false;

        var images = {{ Js::from($images) }};
  
        var myDropzone = new Dropzone(".dropzone", { 
            init: function() { 
                myDropzone = this;

                $.each(images, function(key,value) {
                    var mockFile = { name: value.name, size: value.filesize};
     
                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("thumbnail", mockFile, value.path);
                    myDropzone.emit("complete", mockFile);
          
                });
            },
           autoProcessQueue: false,
           paramName: "files",
           uploadMultiple: true,
           maxFilesize: 5,
           acceptedFiles: ".jpeg,.jpg,.png,.gif",
           addRemoveLinks: false // Disable delete option
        });
      
        $('#uploadFile').click(function(){
           myDropzone.processQueue();
        });
        // Add this code to handle the response after uploading images
        myDropzone.on("success", function(file, response) {
            // Display success message using SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            });
        });
  
</script>
    
@endpush
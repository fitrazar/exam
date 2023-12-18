@extends('layouts.dashboard')

@section('title', 'Dashboard | Tambah Data Jurusan')
@section('content')
    <x-breadcrumb title="Tambah Data Jurusan" url="/dashboard/major" prev="Data Jurusan" />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ url('/dashboard/major') }}" class="mb-4 btn btn-info">Kembali</a>

                            <form class="row g-3" action="{{ url('/dashboard/major') }}" method="POST">
                                @csrf
                                <div class="col-6 mb-3">
                                    <label for="name" class="form-label">Nama Jurusan</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Teknik Komputer Jaringan">
                                    @error('name')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="acronym" class="form-label">Alias</label>
                                    <input type="text" class="form-control @error('acronym') is-invalid @enderror"
                                        id="acronym" name="acronym" value="{{ old('acronym') }}" placeholder="TKJ">
                                    @error('acronym')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug') }}" readonly>
                                    @error('slug')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">

                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="status" name="status"
                                            {{ old('status') == true ? ' ' : ' checked' }}>
                                        <label for="status" class="custom-control-label">Sembunyikan?</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        const judul = document.querySelector("#name");
        const slug = document.querySelector("#slug");

        judul.addEventListener("keyup", function() {
            let preslug = judul.value;
            preslug = preslug.replace(/ /g, "-");
            slug.value = preslug.toLowerCase();
        });
    </script>
@endsection

@extends('layouts.dashboard')

@section('title', 'Dashboard | Tambah Data Kelas')
@section('content')
    <x-breadcrumb title="Edit Data Kelas" url="/dashboard/grade" prev="Data Kelas" />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ url('/dashboard/grade') }}" class="mb-4 btn btn-info">Kembali</a>

                            <form class="row g-3" action="{{ url('/dashboard/grade/' . $grade->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="col-6 mb-3">
                                    <label for="name" class="form-label">Kelas</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $grade->name) }}">
                                    @error('name')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $grade->slug) }}" readonly>
                                    @error('slug')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="status" name="status"
                                            {{ old('status', $grade->status) == true ? ' ' : ' checked' }}>
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

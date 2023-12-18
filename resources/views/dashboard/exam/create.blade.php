@extends('layouts.dashboard')

@section('title', 'Dashboard | Tambah Data Ujian')
@section('content')
    <x-breadcrumb title="Tambah Data Ujian" url="/dashboard/exam" prev="Data Ujian" />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ url('/dashboard/exam') }}" class="mb-4 btn btn-info">Kembali</a>

                            <form class="row g-3" action="{{ url('/dashboard/exam') }}" method="POST">
                                @csrf
                                <div class="col-6 mb-3">
                                    <label for="title" class="form-label">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}">
                                    @error('title')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                                        name="description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="rombel" class="form-label">Rombel</label>
                                    <select class="select2 form-control" style="width: 100%;" name="rombel" id="rombel">
                                        <option value="" disabled selected>Pilih Rombel</option>
                                        @foreach ($rombels as $rombel)
                                            <option value="{{ $rombel['id'] }}"
                                                {{ old('rombel') == $rombel['id'] ? ' selected' : ' ' }}>
                                                {{ $rombel['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rombel')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="date_start" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control @error('date_start') is-invalid @enderror"
                                        id="date_start" name="date_start" value="{{ old('date_start') }}">
                                    @error('date_start')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="time_start" class="form-label">Jam Mulai</label>
                                    <input type="time" class="form-control @error('time_start') is-invalid @enderror"
                                        id="time_start" name="time_start" value="{{ old('time_start') }}">
                                    @error('time_start')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="time_end" class="form-label">Jam Selesai</label>
                                    <input type="time" class="form-control @error('time_end') is-invalid @enderror"
                                        id="time_end" name="time_end" value="{{ old('time_end') }}">
                                    @error('time_end')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
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
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>
@endsection

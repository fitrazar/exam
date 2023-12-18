@extends('layouts.dashboard')

@section('title', 'Dashboard | Tambah Data No Kelas')
@section('content')
    <x-breadcrumb title="Edit Data No Kelas" url="/dashboard/group" prev="Data No Kelas" />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ url('/dashboard/group') }}" class="mb-4 btn btn-info">Kembali</a>

                            <form class="row g-3" action="{{ url('/dashboard/group/' . $group->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="col-6 mb-3">
                                    <label for="number" class="form-label">No Kelas</label>
                                    <input type="text" class="form-control @error('number') is-invalid @enderror"
                                        id="number" name="number" value="{{ old('number', $group->number) }}">
                                    @error('number')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="status" name="status"
                                            {{ old('status', $group->status) == true ? ' ' : ' checked' }}>
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

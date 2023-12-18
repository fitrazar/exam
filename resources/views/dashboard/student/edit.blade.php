@extends('layouts.dashboard')

@section('title', 'Dashboard | Tambah Data Siswa')
@section('content')
    <x-breadcrumb title="Edit Data Siswa" url="/dashboard/student" prev="Data Siswa" />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ url('/dashboard/student') }}" class="mb-4 btn btn-info">Kembali</a>

                            <form class="row g-3" action="{{ url('/dashboard/student/' . $student->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="col-6 mb-3">
                                    <label for="nisn" class="form-label">NISN</label>
                                    <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                        id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                                        placeholder="xxxxxxxx">
                                    @error('nisn')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="name" class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $student->name) }}"
                                        placeholder="Fitra Fajar">
                                    @error('name')
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
                                                {{ old('rombel', $student->grade_id . ' ' . $student->major_id . ' ' . $student->group_id) == $rombel['id'] ? ' selected' : ' ' }}>
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
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-control" style="width: 100%;" name="gender" id="gender">
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki - Laki"
                                            {{ old('gender', $student->gender) == 'Laki - Laki' ? ' selected' : ' ' }}>Laki
                                            - Laki
                                        </option>
                                        <option value="Perempuan"
                                            {{ old('gender', $student->gender) == 'Perempuan' ? ' selected' : ' ' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6 mb-3">
                                    <label for="phone" class="form-label">No HP</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $student->phone) }}"
                                        placeholder="628xxxx">
                                    @error('phone')
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

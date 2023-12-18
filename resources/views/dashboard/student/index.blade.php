@extends('layouts.dashboard')

@section('title', 'Dashboard | Data Siswa')
@section('content')
    <x-breadcrumb title="Data Siswa" url="dashboard" prev="Home" />

    <section class="content">
        <div class="container-fluid">

            @if (session()->has('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Siswa</h3>
                        </div>

                        <div class="card-body">

                            <x-table-button create="/dashboard/student/create" export=""
                                import="/dashboard/student/import" />

                            <table id="example2" class="table table-bordered table-hover">
                                <x-table-heading th="No|NISN|Nama|Kelas|JK|No HP|Poin|Action" />
                                <x-table-body :items="$students" key="nisn|name|has_relation|gender|phone|point"
                                    relation="grade_name|major_acronym|group_number" url="/dashboard/student/"
                                    route="id" />
                            </table>
                        </div>

                    </div>

                </div>
            </div>
    </section>
@endsection

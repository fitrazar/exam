@extends('layouts.dashboard')

@section('title', 'Dashboard | Data Ujian')
@section('content')
    <x-breadcrumb title="Data Ujian" url="dashboard" prev="Home" />

    <section class="content">
        <div class="container-fluid">

            @if (session()->has('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Ujian</h3>
                        </div>

                        <div class="card-body">

                            <x-table-button create="/dashboard/exam/create" export="" import="" />

                            <table id="example2" class="table table-bordered table-hover">
                                <x-table-heading th="No|Judul|Kelas|Tanggal|Action" />
                                <x-table-body :items="$exams" key="title|has_relation|date_start"
                                    relation="grade_name|major_acronym|group_number" url="/dashboard/exam/"
                                    route="id" />
                            </table>
                        </div>

                    </div>

                </div>
            </div>
    </section>
@endsection

@extends('layouts.dashboard')

@section('title', 'Dashboard | Data Jurusan')
@section('content')
    <x-breadcrumb title="Data Jurusan" url="dashboard" prev="Home" />

    <section class="content">
        <div class="container-fluid">

            @if (session()->has('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Jurusan</h3>
                        </div>

                        <div class="card-body">

                            <x-table-button create="/dashboard/major/create" export="" import="" />

                            <table id="example2" class="table table-bordered table-hover">
                                <x-table-heading th="No|Nama|Alias|Slug|Status|Action" />
                                <x-table-body :items="$majors" key="name|acronym|slug|status" relation=""
                                    url="/dashboard/major/" route="id" />
                            </table>
                        </div>

                    </div>

                </div>
            </div>
    </section>
@endsection

@extends('layouts.dashboard')

@section('title', 'Dashboard | Data Kelas')
@section('content')
    <x-breadcrumb title="Data Kelas" url="dashboard" prev="Home" />

    <section class="content">
        <div class="container-fluid">

            @if (session()->has('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Kelas</h3>
                        </div>

                        <div class="card-body">

                            <x-table-button create="/dashboard/grade/create" export="" import="" />

                            <table id="example2" class="table table-bordered table-hover">
                                <x-table-heading th="No|Nama|Slug|Status|Action" />
                                <x-table-body :items="$grades" key="name|slug|status" relation=""
                                    url="/dashboard/grade/" route="id" />
                            </table>
                        </div>

                    </div>

                </div>
            </div>
    </section>
@endsection

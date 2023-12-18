@extends('layouts.dashboard')

@section('title', 'Dashboard | Data No Kelas')
@section('content')
    <x-breadcrumb title="Data No Kelas" url="dashboard" prev="Home" />

    <section class="content">
        <div class="container-fluid">

            @if (session()->has('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data No Kelas</h3>
                        </div>

                        <div class="card-body">

                            <x-table-button create="/dashboard/group/create" export="" import="" />

                            <table id="example2" class="table table-bordered table-hover">
                                <x-table-heading th="No|No Kelas|Status|Action" />
                                <x-table-body :items="$groups" key="number|status" relation="" url="/dashboard/group/"
                                    route="id" />
                            </table>
                        </div>

                    </div>

                </div>
            </div>
    </section>
@endsection

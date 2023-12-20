@section('title', 'Siswa')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow rounded-3 my-2">
                <div class="card-header">Siswa Page</div>
                @php
                    $time = date('H');
                    $greeting = '';

                    if ($time >= 5 && $time < 10) {
                        $greeting = 'Selamat pagi!';
                    } elseif ($time >= 10 && $time < 15) {
                        $greeting = 'Selamat siang!';
                    } elseif ($time >= 15 && $time < 18) {
                        $greeting = 'Selamat sore!';
                    } else {
                        $greeting = 'Selamat malam!';
                    }
                @endphp
                <div class="card-body">
                    {{ $greeting }} {{ auth()->user()->name }}

                    <br>
                    <p>Kamu ada {{ count($exams) }} ulangan hari ini.</p>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @foreach ($exams as $exam)
                                    @php
                                        $currentTime = now();

                                        $timeStart = \Carbon\Carbon::createFromFormat('H:i:s', $exam->time_start);
                                        $timeEnd = \Carbon\Carbon::createFromFormat('H:i:s', $exam->time_end);
                                        $dateStart = \Carbon\Carbon::createFromFormat('Y-m-d', $exam->date_start);

                                        $isActive = $currentTime->lte($timeStart) || $currentTime->gte($timeEnd);
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $exam->code }}</td>
                                        <td>{{ $exam->title }}</td>
                                        <td>{{ $exam->time_start }} - {{ $exam->time_end }}</td>
                                        <td>{{ $isActive ? 'Tidak Aktif' : 'Aktif' }}</td>
                                        <td>{!! $isActive ? '-' : '<a href="' . url('/exam/' . $exam->code) . '" wire:navigate>Ikut</a>' !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        localStorage.clear();
    </script>
@endscript

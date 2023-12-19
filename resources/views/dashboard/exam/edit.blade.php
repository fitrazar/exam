@extends('layouts.dashboard')

@section('title', 'Dashboard | Edit Data Ujian')
@section('content')
    <x-breadcrumb title="Edit Data Ujian" url="/dashboard/exam" prev="Data Ujian" />

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="alert alert-success col-lg-8" role="alert" style="display: none;" id="messagediv">
                    <p id="message"></p>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ url('/dashboard/exam') }}" class="mb-4 btn btn-info">Kembali</a>

                            <form class="row g-3" action="{{ url('/dashboard/exam/' . $exam->code) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="col-6 mb-3">
                                    <label for="title" class="form-label">Judul</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $exam->title) }}">
                                    @error('title')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                                        name="description">{{ old('description', $exam->description) }}</textarea>
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
                                                {{ old('rombel', $exam->grade_id . ' ' . $exam->major_id . ' ' . $exam->group_id) == $rombel['id'] ? ' selected' : ' ' }}>
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
                                        id="date_start" name="date_start"
                                        value="{{ old('date_start', $exam->date_start) }}">
                                    @error('date_start')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="time_start" class="form-label">Jam Mulai</label>
                                    <input type="time" class="form-control @error('time_start') is-invalid @enderror"
                                        id="time_start" name="time_start"
                                        value="{{ old('time_start', $exam->time_start) }}">
                                    @error('time_start')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="time_end" class="form-label">Jam Selesai</label>
                                    <input type="time" class="form-control @error('time_end') is-invalid @enderror"
                                        id="time_end" name="time_end" value="{{ old('time_end', $exam->time_end) }}">
                                    @error('time_end')
                                        <span class="text-danger text-sm" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>


                                @php
                                    $dataToStore = [];

                                    // Looping untuk mengisi data ke dalam array PHP
                                    foreach ($questions as $index => $question) {
                                        foreach ($question->answerOptions as $answer) {
                                            // Buat array asosiatif yang akan dimasukkan ke dalam $dataToStore
                                            $teksArray = explode("\n", $question->question);

                                            $pilihanArray = [];

                                            foreach ($teksArray as $baris) {
                                                $bagian = explode('. ', $baris, 2);
                                                if (count($bagian) === 2) {
                                                    $pilihanArray[$bagian[0]] = $bagian[1];
                                                }
                                            }
                                            $loopVal = [
                                                'id' => $question->id,
                                                'type' => $question->type,
                                                'essay' => Str::before($question->question, 'A. '),
                                                'option_1' => $pilihanArray['A'] ?? '',
                                                'option_2' => $pilihanArray['B'] ?? '',
                                                'option_3' => $pilihanArray['C'] ?? '',
                                                'option_4' => $pilihanArray['D'] ?? '',
                                                'option_5' => $pilihanArray['E'] ?? '',
                                                'answer' => $answer->option,
                                            ];

                                            // Masukkan nilai ke dalam $dataToStore
                                            $dataToStore[] = $loopVal;
                                        }
                                    }
                                @endphp

                                <div class="col-12 mb-4">
                                    <hr>
                                    <h4 class="text-bold text-center mt-4">Pertanyaan</h4>
                                    <div class="p-3 clone-row" id="dynamic">
                                        <label for="question" class="form-label">Pertanyaan <span
                                                class="q-number">1</span></label>
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Tipe Soal</label>
                                            <select class="form-control" name="type[]" id="type[]" required>
                                                <option value="" disabled selected>Pilih Tipe Soal</option>
                                                <option value="0">Pilihan Ganda</option>
                                                <option value="1">Pilihan Ganda Kompleks</option>
                                                <option value="2">Essay</option>
                                            </select>
                                        </div>

                                        <div id="textarea" class="mb-3">

                                            <textarea name="question[]" class="form-control" required>{!! old('question[]') !!}</textarea>
                                        </div>

                                        <div id="pg" class="pg d-flex flex-row mb-3">
                                            <x-multiple-choice />
                                        </div>
                                        <div id="answer" class="mb-3">
                                            <label for="answer" class="form-label">Jawaban</label>
                                            <input type="text" name="answer[]" class="form-control" required>
                                            <span class="text-sm text-bold">*Note : Untuk PG cukup ketik A,B,C,D atau E.
                                                Untuk PG
                                                Kompleks misalnya A|B. Berarti A & B adalah jawabannya</span>
                                        </div>

                                        <div class="btn btn-danger btn-del-select" role="alert"
                                            onclick="confirmDelete(this)">
                                            <span class="font-medium">Hapus
                                                <input type="hidden" name="id_qa[]" id="id_qa">
                                        </div>




                                        <hr>
                                    </div>
                                    <button type="button" name="add" id="add" class="btn btn-info">Tambah
                                        Pertanyaan</button>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" id="save">Simpan</button>
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
    <script>
        $('.btn-del-select').hide();
        $('#pg').removeClass('d-flex');
        $('#textarea, #pg, #answer').prop('required', false).hide();
        let num = $('.clone-row').length;


        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            updateLocalStorageFromLoop()
            $('#type\\[\\]').change(function() {
                let selectedType = $(this).val();

                if (selectedType == 0 || selectedType == 1) {
                    $('#pg').addClass('d-flex').show();
                    $('#pg .input-group input').prop('required', true);
                    $('#answer').prop('required', true).show();
                    $('#textarea').show();
                    $('#textarea textarea').prop('required', true).show();
                } else if (selectedType == 2) {
                    $('#textarea').show();
                    $('#textarea textarea').prop('required', true).show();
                    $('#answer').prop('required', true).show();
                    $('#pg').removeClass('d-flex').hide();
                    $('#pg .input-group input').prop('required', false);
                }
            });

            addClonedForm();

            $(document).on('click', '#add', function() {
                addForm('');
            });
            $(document).on('click', '#save', function() {
                updateLocalStorage();
            });

            // $(document).on('click', '.btn-del-select', function(e) {
            //     e.preventDefault();
            //     // console.log($(this).find('input[name="id_qa[]"]').val());
            //     if ($(this).find('input[name="id_qa[]"]').val() != '') {
            //         $(this).parent().remove();
            //         num--;
            //         updateNumbers();
            //         updateLocalStorage();
            //         let q_id = $(this).val();
            //         let thisClick = $(this);
            //         $.ajax({
            //             type: "GET",
            //             url: "/dashboard/exam/question/" + q_id + "/delete",
            //             success: function(response) {
            //                 let a = document.getElementById("messagediv")
            //                 a.style.display = "block";
            //                 document.getElementById("message").innerHTML = response.message;
            //             }
            //         });
            //     } else {
            //         $(this).parent().remove();
            //         num--;
            //         updateNumbers();
            //         updateLocalStorage();
            //     }


            // });
        });

        function confirmDelete(e) {
            if (confirm('Kamu yakin ingin menghapus data ini?')) {
                // Ambil nilai id_qa
                let q_id = $(e).find('.id_qa').val();

                if (q_id != '') {
                    $(e).parent().remove();
                    num--;
                    updateNumbers();
                    updateLocalStorage();

                    $.ajax({
                        type: "GET",
                        url: "/dashboard/exam/question/" + q_id + "/delete",
                        success: function(response) {
                            let a = document.getElementById("messagediv")
                            a.style.display = "block";
                            document.getElementById("message").innerHTML = response.message;
                        }
                    });
                } else {
                    $(e).parent().remove();
                    num--;
                    updateNumbers();
                    updateLocalStorage();
                }
            }
        }


        function addClonedForm() {

            let storedData = localStorage.getItem('clonedFormData');
            if (storedData) {
                let parsedData = JSON.parse(storedData);


                $('.clone-row').each(function(index) {
                    if (parsedData[0].type == 0 || parsedData[0].type == 1) {
                        $(this).find('select[name="type[]"]').val(parsedData[0].type).change();
                        $(this).find('input[name="option_1[]"]').val(parsedData[0].option_1);
                        $(this).find('input[name="option_2[]"]').val(parsedData[0].option_2);
                        $(this).find('input[name="option_3[]"]').val(parsedData[0].option_3);
                        $(this).find('input[name="option_4[]"]').val(parsedData[0].option_4);
                        $(this).find('input[name="option_5[]"]').val(parsedData[0].option_5);
                        $(this).find('textarea[name="question[]"]').val(parsedData[0].essay);
                        $(this).find('input[name="answer[]"]').val(parsedData[0].answer);
                        $(this).find('input[name="id_qa[]"]').val(parsedData[0].id);
                    } else if (parsedData[0].type == 2) {
                        $(this).find('select[name="type[]"]').val(parsedData[0].type).change();
                        $(this).find('textarea[name="question[]"]').val(parsedData[0].essay);
                        $(this).find('input[name="answer[]"]').val(parsedData[0].answer);
                        $(this).find('input[name="id_qa[]"]').val(parsedData[0].id);
                    }
                });

                for (let i = 1; i < parsedData.length; i++) {
                    addForm(parsedData[i]);
                }
            }
        }

        function addForm(value) {
            num++;
            let clonedForm = $('.clone-row').first().clone().insertAfter('.clone-row:last');
            clonedForm.find('.q-number').text(num);


            clonedForm.find('#pg').removeClass('d-flex');
            clonedForm.find('#textarea, #pg, #answer').prop('required', false).hide();
            clonedForm.find('#pg .input-group input').val('');
            clonedForm.find('#textarea textarea').val('');
            clonedForm.find('#answer input').val('');
            clonedForm.find('.btn-del-select input').val('');

            clonedForm.find('#type\\[\\]').change(function() {
                let selectedType = $(this).val();

                if (selectedType == 0 || selectedType == 1) {
                    clonedForm.find('#pg').addClass('d-flex').show();
                    clonedForm.find('#pg .input-group input').prop('required', true);
                    clonedForm.find('#answer').prop('required', true).show();
                    clonedForm.find('#textarea').show();
                    clonedForm.find('#textarea textarea').prop('required', true).show();
                } else if (selectedType == 2) {
                    clonedForm.find('#textarea').show();
                    clonedForm.find('#textarea textarea').prop('required', true).show();
                    clonedForm.find('#answer').prop('required', true).show();
                    clonedForm.find('#pg').removeClass('d-flex').hide();
                    clonedForm.find('#pg .input-group input').prop('required', false);
                }
            });


            if (value.type == 0 || value.type == 1) {
                clonedForm.find('select[name="type[]"]').val(value.type).change();
                clonedForm.find('input[name="option_1[]"]').val(value.option_1);
                clonedForm.find('input[name="option_2[]"]').val(value.option_2);
                clonedForm.find('input[name="option_3[]"]').val(value.option_3);
                clonedForm.find('input[name="option_4[]"]').val(value.option_4);
                clonedForm.find('input[name="option_5[]"]').val(value.option_5);
                clonedForm.find('textarea[name="question[]"]').val(value.essay);
                clonedForm.find('input[name="answer[]"]').val(value.answer);
                clonedForm.find('input[name="id_qa[]"]').val(value.id);
            } else if (value.type == 2) {
                console.log(value.type);
                clonedForm.find('select[name="type[]"]').val(value.type).change();
                clonedForm.find('textarea[name="question[]"]').val(value.essay);
                clonedForm.find('input[name="answer[]"]').val(value.answer);
                clonedForm.find('input[name="id_qa[]"]').val(value.id);
            }

            clonedForm.find('.btn-del-select').fadeIn();

        }

        function updateNumbers() {
            $('.q-number').each(function(index) {
                $(this).text(index + 1);
            });
        }

        function updateLocalStorage() {
            let dataToStore = [];

            $('.clone-row').each(function() {
                let inputVal = {
                    'id': $(this).find('input[name="id_qa[]"]').val() ?? '',
                    'type': $(this).find('select[name="type[]"]').val(),
                    'essay': $(this).find('textarea[name="question[]"]').val(),
                    'option_1': $(this).find('input[name="option_1[]"]').val(),
                    'option_2': $(this).find('input[name="option_2[]"]').val(),
                    'option_3': $(this).find('input[name="option_3[]"]').val(),
                    'option_4': $(this).find('input[name="option_4[]"]').val(),
                    'option_5': $(this).find('input[name="option_5[]"]').val(),
                    'answer': $(this).find('input[name="answer[]"]').val(),

                };
                dataToStore.push(inputVal);
            });

            localStorage.setItem('clonedFormData', JSON.stringify(dataToStore));
        }

        function updateLocalStorageFromLoop() {
            // Cek apakah 'clonedFormData' sudah ada di localStorage
            let existingData = localStorage.getItem('clonedFormData');

            // Jika 'clonedFormData' belum ada di localStorage
            if (existingData === null) {
                let dataToStoreJS = @json($dataToStore);
                localStorage.setItem('clonedFormData', JSON.stringify(dataToStoreJS));
            }
            // Jika 'clonedFormData' sudah ada, tidak melakukan reset
            else {
                console.log('Data sudah ada di localStorage. Tidak melakukan reset.');
            }
        }
    </script>
@endsection

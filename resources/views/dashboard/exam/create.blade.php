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

                                        <div class="btn btn-danger btn-del-select" role="alert">
                                            <span class="font-medium">Hapus
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

            $(document).on('click', '.btn-del-select', function(e) {
                e.preventDefault();
                $(this).parent().remove();
                num--;
                updateNumbers();
                updateLocalStorage();
            });
        });

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
                    } else if (parsedData[0].type == 2) {
                        $(this).find('select[name="type[]"]').val(parsedData[0].type).change();
                        $(this).find('textarea[name="question[]"]').val(parsedData[0].essay);
                        $(this).find('input[name="answer[]"]').val(parsedData[0].answer);
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
            } else if (value.type == 2) {
                console.log(value.type);
                clonedForm.find('select[name="type[]"]').val(value.type).change();
                clonedForm.find('textarea[name="question[]"]').val(value.essay);
                clonedForm.find('input[name="answer[]"]').val(value.answer);
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
    </script>
@endsection

@section('title', 'Ujian')

<div>


    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="split">
                    <div class="card card-margin">
                        <div class="card-body pt-0 p-3">
                            <div class="widget-49">
                                <div class="widget-49-title-wrapper">
                                    <h2>Mau split screen kah?</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-custom card-margin">
                    <div class="{{ $currentStep == 0 ? 'd-block' : 'd-none' }}">
                        <div class="card-header-custom no-border">
                            <h5 class="card-title">{{ $exam->title }}</h5>
                        </div>
                        <p style="padding: 10px;">{{ $exam->description }}</p>
                        <div class="card-body pt-0">

                            @if ($currentStep === 0)
                                <div class="widget-49">
                                    <div class="widget-49-title-wrapper">
                                        <div class="widget-49-date-primary">
                                            <span
                                                class="widget-49-date-day">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $exam->date_start)->format('d') }}</span>
                                            <span
                                                class="widget-49-date-month">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $exam->date_start)->format('M') }}</span>
                                        </div>
                                        <div class="widget-49-meeting-info">
                                            <span class="widget-49-pro-title">{{ $diffInMinutes }} Menit</span>
                                            <span class="widget-49-meeting-time">{{ $exam->time_start }} -
                                                {{ $exam->time_end }} ({{ $exam->questions->count() }} Soal)
                                            </span>
                                        </div>
                                    </div>
                                    <ol class="widget-49-meeting-points">
                                        <li class="widget-49-meeting-item"><span>Baca soal dengan baik dan benar.</span>
                                        </li>
                                        <li class="widget-49-meeting-item"><span>Kerjakan soal lain dulu jika dirasa
                                                soal
                                                sebelumnya sulit.</span></li>
                                        <li class="widget-49-meeting-item"><span>Dilarang menyontek
                                                AI/Google/Teman/Buku.</span>
                                        <li class="widget-49-meeting-item"><span>Gunakan waktu semaksimal
                                                mungkin.</span>
                                        <li class="widget-49-meeting-item"><span>Berapapun nilai kamu, jika kamu
                                                mengerjakan
                                                nya
                                                secara jujur kamu adalah pemenangnya.</span>
                                        </li>
                                        <li class="widget-49-meeting-item"><span>Good luck.</span>
                                        </li>
                                    </ol>

                                    <div class="d-flex justify-content-between p-3">

                                        <button class="btn btn-sm btn-primary mt-4" wire:click="zeroStepSubmit"
                                            type="button" wire:loading.attr="disabled">Berikutnya</button>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>


                    <div class="card-body pt-0">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        @php $questionNumber = 1; @endphp

                        <form wire:submit.prevent="submitForm">

                            <div class="{{ $currentStep == 1 ? 'd-block' : 'd-none' }}">
                                @if ($currentStep === 1)
                                    <div class="widget-49 p-3">
                                        <h5 class="card-title">Pilihan Ganda</h5>
                                        @php $radioId = 1; @endphp
                                        @foreach ($exam->questions as $key => $question)
                                            @if ($question->type == 0)
                                                @php
                                                    $teksArray = explode("\n", $question->question);

                                                    $pilihanArray = [];

                                                    foreach ($teksArray as $baris) {
                                                        $bagian = explode('. ', $baris, 2);
                                                        if (count($bagian) === 2) {
                                                            $pilihanArray[$bagian[0]] = $bagian[1];
                                                        }
                                                    }
                                                @endphp
                                                <p class="widget-49-head mt-3 user-select-none">{{ $questionNumber }}.
                                                    {{ Str::before($question->question, 'A. ') }}
                                                    ({{ $question->score }}
                                                    poin)
                                                </p>
                                                @foreach ($pilihanArray as $key => $pilihan)
                                                    <div class="form-check widget-49-text" wire:ignore>
                                                        <input class="form-check-input" type="radio"
                                                            name="radio_{{ $radioId }}"
                                                            id="radio_{{ $radioId }}" value="{{ $key }}"
                                                            wire:model="answers.{{ $question->id }}">
                                                        <label class="form-check-label"
                                                            for="radio_{{ $radioId }}">
                                                            {{ $pilihan }}
                                                        </label>
                                                        @php $radioId++; @endphp

                                                    </div>
                                                @endforeach
                                                <hr>
                                                @php $questionNumber++; @endphp
                                            @endif
                                        @endforeach
                                        <div class="d-flex justify-content-between p-3">
                                            <button class="btn btn-sm btn-danger mt-4" wire:click="back(0)"
                                                type="button" wire:loading.attr="disabled">Kembali</button>
                                            <button class="btn btn-sm btn-primary mt-4" wire:click="firstStepSubmit"
                                                type="button" wire:loading.attr="disabled">Berikutnya</button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="{{ $currentStep == 2 ? 'd-block' : 'd-none' }}">
                                @if ($currentStep === 2)
                                    <div class="widget-49 p-3">
                                        <h5 class="card-title"> Pilihan Ganda Kompleks </h5>
                                        @php $checkboxId = 1; @endphp
                                        @foreach ($exam->questions as $key => $question)
                                            @if ($question->type == 1)
                                                @php
                                                    $teksArray = explode("\n", $question->question);

                                                    $pilihanArray = [];

                                                    foreach ($teksArray as $baris) {
                                                        $bagian = explode('. ', $baris, 2);
                                                        if (count($bagian) === 2) {
                                                            $pilihanArray[$bagian[0]] = $bagian[1];
                                                        }
                                                    }
                                                @endphp

                                                <p class="widget-49-head mt-3 user-select-none">{{ $questionNumber }}.
                                                    {{ Str::before($question->question, 'A. ') }}
                                                    ({{ $question->score }}
                                                    poin)
                                                </p>
                                                @foreach ($pilihanArray as $key => $pilihan)
                                                    <div class="form-check widget-49-text" wire:ignore>
                                                        <input class="form-check-input" type="checkbox"
                                                            name="checkbox_{{ $checkboxId }}"
                                                            id="checkbox_{{ $checkboxId }}"
                                                            value="{{ $key }}"
                                                            wire:model="answers.{{ $question->id }}.{{ $key }}">
                                                        <label class="form-check-label"
                                                            for="checkbox_{{ $checkboxId }}">
                                                            {{ $pilihan }}
                                                        </label>
                                                        @php $checkboxId++; @endphp
                                                    </div>
                                                @endforeach
                                                <hr>
                                                @php $questionNumber++; @endphp
                                            @endif
                                        @endforeach
                                        <div class="d-flex justify-content-between p-3">
                                            <button class="btn btn-sm btn-danger mt-4" wire:click="back(1)"
                                                type="button" wire:loading.attr="disabled">Kembali</button>
                                            <button class="btn btn-sm btn-primary mt-4" wire:click="secondStepSubmit"
                                                type="button" wire:loading.attr="disabled">Berikutnya</button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="{{ $currentStep == 3 ? 'd-block' : 'd-none' }}">
                                @if ($currentStep === 3)
                                    <div class="widget-49 p-3">
                                        <h5 class="card-title"> Essay </h5>

                                        @foreach ($exam->questions as $key => $question)
                                            @if ($question->type == 2)
                                                <p class="widget-49-head mt-3 user-select-none">{{ $questionNumber }}.
                                                    {{ $question->question }}
                                                    ({{ $question->score }}
                                                    poin)
                                                </p>
                                                <div class="form-group mb-3" wire:ignore>
                                                    <textarea wire:model="answers.{{ $question->id }}" class="form-control" name="answers{{ $question->id }}"
                                                        id="answers{{ $question->id }}"></textarea>
                                                </div>
                                                <hr>
                                                @php $questionNumber++; @endphp
                                            @endif
                                        @endforeach
                                        <div class="d-flex justify-content-between p-3">
                                            <button class="btn btn-sm btn-danger mt-4" wire:click="back(2)"
                                                type="button" wire:loading.attr="disabled">Kembali</button>
                                            <button class="btn btn-sm btn-primary mt-4" type="submit"
                                                wire:loading.attr="disabled">Selesai</button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </form>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>
@script
    <script>
        document.addEventListener("visibilitychange", () => {
            if (document.visibilityState !== 'visible') {
                $wire.dispatch('hayo');
                document.title = 'Aku tau kamu nyontek';
            } else {
                document.title = 'Ujian';
            }
        });
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('enterFullscreen', (event) => {
                const element = document.documentElement;
                const requestFullScreen = element.requestFullscreen || element.webkitRequestFullscreen ||
                    element.mozRequestFullScreen || element.msRequestFullscreen;

                if (requestFullScreen) {
                    requestFullScreen.call(element);
                }
            });

            Livewire.hook('element.init', (component, el) => {

                const auth = localStorage.getItem('auth');
                if (auth == '{{ auth()->user()->id }}') {
                    let inputAll = $wire.$el.querySelectorAll('input, textarea');

                    inputAll.forEach((form) => {
                        let fieldName1 = form.getAttribute('wire:model');
                        let storedValue = localStorage.getItem(fieldName1);
                        // console.log(storedValue);

                        if (storedValue !== null) {
                            if (form.type === 'checkbox' || form.type === 'radio') {
                                form.checked = storedValue;
                            } else {
                                form.value = storedValue;
                            }
                            // console.log($wire.$el.getAttribute('id'));
                            Livewire.find($wire.$id).set(fieldName1, storedValue);
                        }
                    });
                }




                let inputElements = $wire.$el.querySelectorAll('input, textarea');
                // console.log(inputElements);

                inputElements.forEach((input) => {
                    input.addEventListener('input', function() {
                        let fieldName = input.getAttribute('wire:model');
                        let fieldValue = input.value;
                        let checkbox = input.checked;
                        localStorage.setItem('auth',
                            {{ auth()->user()->id }});
                        if (!checkbox) {
                            // console.log(input.tagName);
                            if (input.tagName && input.tagName.toLowerCase() ==
                                "textarea") {
                                localStorage.setItem(fieldName, fieldValue);
                            } else {
                                localStorage.removeItem(fieldName);
                            }
                        } else {
                            localStorage.setItem(fieldName, fieldValue);

                        }

                    });
                });
            });

        });
    </script>
@endscript

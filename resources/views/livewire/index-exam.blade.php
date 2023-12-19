@section('title', 'Ujian')

<div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header">Jawab Pertanyaan Dibawah Dengan Sungguh Sungguh & Jujur.</div>
                    <div class="card-body">

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
                                    <h3> Pilihan Ganda </h3>
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
                                            <p class="text-bold mt-3">{{ $questionNumber }}.
                                                {{ Str::before($question->question, 'A. ') }} ({{ $question->score }}
                                                poin)
                                            </p>
                                            @foreach ($pilihanArray as $key => $pilihan)
                                                <div class="form-check" wire:ignore>
                                                    <input class="form-check-input" type="radio"
                                                        name="radio_{{ $radioId }}" id="radio_{{ $radioId }}"
                                                        value="{{ $key }}"
                                                        wire:model.defer="answers.{{ $question->id }}">
                                                    <label class="form-check-label" for="radio_{{ $radioId }}">
                                                        {{ $pilihan }}
                                                    </label>
                                                    @php $radioId++; @endphp

                                                </div>
                                            @endforeach

                                            @php $questionNumber++; @endphp
                                        @endif
                                    @endforeach
                                    <button class="btn btn-primary mt-4" wire:click="firstStepSubmit" type="button"
                                        wire:loading.attr="disabled">Next</button>
                                @endif
                            </div>

                            <div class="{{ $currentStep == 2 ? 'd-block' : 'd-none' }}">
                                @if ($currentStep === 2)
                                    <h3> Pilihan Ganda Kompleks </h3>
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

                                            <p class="text-bold mt-3">{{ $questionNumber }}.
                                                {{ Str::before($question->question, 'A. ') }} ({{ $question->score }}
                                                poin)
                                            </p>
                                            @foreach ($pilihanArray as $key => $pilihan)
                                                <div class="form-check" wire:ignore>
                                                    <input class="form-check-input" type="checkbox"
                                                        name="checkbox_{{ $checkboxId }}"
                                                        id="checkbox_{{ $checkboxId }}" value="{{ $key }}"
                                                        wire:model.defer="answers.{{ $question->id }}.{{ $key }}">
                                                    <label class="form-check-label" for="checkbox_{{ $checkboxId }}">
                                                        {{ $pilihan }}
                                                    </label>
                                                    @php $checkboxId++; @endphp
                                                </div>
                                            @endforeach
                                            @php $questionNumber++; @endphp
                                        @endif
                                    @endforeach
                                    <button class="btn btn-primary mt-4" wire:click="secondStepSubmit" type="button"
                                        wire:loading.attr="disabled">Next</button>
                                    <button class="btn btn-danger mt-4" wire:click="back(1)" type="button"
                                        wire:loading.attr="disabled">Kembali</button>
                                @endif
                            </div>

                            <div class="{{ $currentStep == 3 ? 'd-block' : 'd-none' }}">
                                @if ($currentStep === 3)
                                    <h3> Essay </h3>
                                    @foreach ($exam->questions as $key => $question)
                                        @if ($question->type == 2)
                                            <p class="text-bold mt-3">{{ $questionNumber }}. {{ $question->question }}
                                                ({{ $question->score }}
                                                poin)
                                            </p>
                                            <div class="form-group mb-3">
                                                <textarea wire:model="answers.{{ $question->id }}" class="form-control" name="answers{{ $question->id }}"
                                                    id="answers{{ $question->id }}"></textarea>
                                            </div>
                                            @php $questionNumber++; @endphp
                                        @endif
                                    @endforeach

                                    <button class="btn btn-success" type="submit">Selesai</button>
                                    <button class="btn btn-danger" wire:click="back(2)" type="button"
                                        wire:loading.attr="disabled">Kembali</button>
                                @endif
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

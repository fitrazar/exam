<div class="d-flex">
    @if ($create)
        <div class="p-2 flex-grow-1">
            <a href="{{ url($create) }}" class="btn btn-primary mb-3">Tambah Data</a>
        </div>
    @endif
    @if ($export)
        <div class="p-2">
            <a href="{{ url($export) }}" class="btn btn-success mb-3">Export Data</a>
        </div>
    @endif
</div>

@if ($import)
    <form action="{{ url($import) }}" method="post" enctype="multipart/form-data" class="row g-3 mb-4">
        @csrf
        <div class="input-group input-group col-6">
            <input type="file" class="form-control" id="file" name="file" required>
            <span class="input-group-append">
                <button type="submit" class="btn btn-info btn-flat">Import</button>
            </span>
        </div>

    </form>
@endif

<tbody>
    @foreach ($items as $item)
        <tr>
            <th scope="row">
                {{ $loop->iteration }}
            </th>
            @foreach (explode('|', $key) as $value)
                <td>
                    @if ($value === 'status' && isset($item->$value))
                        @if ($item->$value === 1)
                            Aktif
                        @elseif ($item->$value === 0)
                            Tidak Aktif
                        @else
                            {{ $item->$value ?? '' }}
                        @endif
                    @elseif ($value === 'has_relation')
                        @foreach (explode('|', $relation) as $to)
                            @php
                                $model = Str::before($to, '_');
                                $val = Str::after($to, '_');
                            @endphp
                            {{ $item->$model->$val ?? '' }}
                        @endforeach
                    @else
                        {{ $item->$value ?? '' }}
                    @endif
                </td>
            @endforeach
            <td>
                <a href="{{ url($url . $item->$route . '/edit') }}" class="btn btn-primary">
                    Edit
                </a>
                <form action="{{ url($url . $item->$route) }}" method="post" class="d-inline">
                    @method('delete')
                    @csrf
                    <button class="btn btn-danger"
                        onclick="return confirm('Kamu yakin ingin menghapus data ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

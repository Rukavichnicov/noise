@php /** @var App\Models\NoiseSource $item */ @endphp
<td rowspan="{{ $arrayRowSpan[$i] }}"> {{$item->fileNoiseSource->foundation}} </td>
<td rowspan="{{ $arrayRowSpan[$i] }}">
    <a href="{{ $item->urlFileNotCheck }}" target="_blank">Файл</a>
</td>
<td rowspan="{{ $arrayRowSpan[$i] }}">
    <form action="{{ route('noise.admin.sources.approve', $item->id_file_path) }}" method="post">
        @csrf
        @method('PATCH')
        <button type="submit" class="form-control-sm btn-success">
            Согласовать
        </button>
    </form>
</td>
<td rowspan="{{ $arrayRowSpan[$i] }}">
    <form action="{{ route('noise.admin.sources.destroy', $item->id_file_path) }}" method="post">
        @method('DELETE')
        @csrf
        <button type="submit" class="form-control-sm btn-danger sm">
            Удалить
        </button>
    </form>
</td>

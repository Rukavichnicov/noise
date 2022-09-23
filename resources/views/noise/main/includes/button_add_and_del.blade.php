@php /** @var NoiseSource $item */ @endphp
@if (true)
    <form action="{{ route('noise.main.basket.store') }}" method="post">
        @csrf
        <button class="form-control-sm btn-primary"
                name="addSources"
                value="{{ $item->id }}"
                title="Добавить в набор">+</button>
    </form>
@else
    <form action="{{ route('noise.main.basket.destroy', $item->id) }}" method="post">
        @csrf
        @method('DELETE')
        <button class="form-control-sm btn-danger"
                name="delSources"
                value="{{ $item->id }}"
                title="Удалить из набора">-</button>
    </form>
@endif

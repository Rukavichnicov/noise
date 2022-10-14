@php use App\Models\NoiseSource; @endphp
<table class="table table-sm table-striped align-middle small text-center">
    <thead class="align-middle">
    <tr>
        <th>Наименование источника шума</th>
        <th>Марка</th>
        <th>Дистанция замера</th>
        <th>31.5</th>
        <th>63</th>
        <th>125</th>
        <th>250</th>
        <th>500</th>
        <th>1000</th>
        <th>2000</th>
        <th>4000</th>
        <th>8000</th>
        <th>La экв</th>
        <th>La макс</th>
        <th>Обоснование</th>
        <th>Примечание</th>
        <th>Файл</th>
        @auth()
            <th>Сбор списка</th>
        @endauth
    </tr>
    </thead>
    <tbody>
    @foreach($paginator as $item)
        @php /** @var NoiseSource $item */ @endphp
        <tr>
            <td> {{$item->name}} </td>
            <td> {{$item->mark}} </td>
            <td> {{$item->distance}} </td>
            <td> {{$item->la_31_5}} </td>
            <td> {{$item->la_63}} </td>
            <td> {{$item->la_125}} </td>
            <td> {{$item->la_250}} </td>
            <td> {{$item->la_500}} </td>
            <td> {{$item->la_1000}} </td>
            <td> {{$item->la_2000}} </td>
            <td> {{$item->la_4000}} </td>
            <td> {{$item->la_8000}} </td>
            <td> {{$item->la_eq}} </td>
            <td> {{$item->la_max}} </td>
            <td> {{$item->fileNoiseSource->foundation}} </td>
            <td> {{$item->remark}} </td>
            <td>
                <a href="{{ $item->urlFileCheck }}" target="_blank">Файл</a>
            </td>
            @auth()
                <td>
                    @if ($item->isThereSourceInBasket)
                        <form action="{{ route('noise.main.basket.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="form-control-sm btn-danger"
                                    name="delSources"
                                    value="{{ $item->id }}"
                                    title="Удалить из набора">-</button>
                        </form>
                    @else
                        <form action="{{ route('noise.main.basket.store') }}" method="post">
                            @csrf
                            <button class="form-control-sm btn-primary"
                                    name="addSources"
                                    value="{{ $item->id }}"
                                    title="Добавить в набор">+</button>
                        </form>
                    @endif
                </td>
            @endauth
        </tr>
    @endforeach
    </tbody>
</table>

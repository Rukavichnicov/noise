@php use App\Models\Basket; @endphp
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
        <th>Удалить</th>
    </tr>
    </thead>
    <tbody>
    @foreach($paginator as $item)
        @php /** @var Basket $item */ @endphp
        <tr>
            <td> {{$item->noiseSource->name}} </td>
            <td> {{$item->noiseSource->mark}} </td>
            <td> {{$item->noiseSource->distance}} </td>
            <td> {{$item->noiseSource->la_31_5}} </td>
            <td> {{$item->noiseSource->la_63}} </td>
            <td> {{$item->noiseSource->la_125}} </td>
            <td> {{$item->noiseSource->la_250}} </td>
            <td> {{$item->noiseSource->la_500}} </td>
            <td> {{$item->noiseSource->la_1000}} </td>
            <td> {{$item->noiseSource->la_2000}} </td>
            <td> {{$item->noiseSource->la_4000}} </td>
            <td> {{$item->noiseSource->la_8000}} </td>
            <td> {{$item->noiseSource->la_eq}} </td>
            <td> {{$item->noiseSource->la_max}} </td>
            <td> {{$item->fileNoiseSource->foundation}} </td>
            <td> {{$item->noiseSource->remark}} </td>
            <td>
                <a href="{{ $item->urlFileCheck }}" target="_blank">Файл</a>
            </td>
            <td>
                <form action="{{ route('noise.main.basket.destroy', $item->noiseSource->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="form-control-sm btn-danger"
                            name="delSources"
                            value="{{ $item->noiseSource->id }}"
                            title="Удалить из набора">-</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

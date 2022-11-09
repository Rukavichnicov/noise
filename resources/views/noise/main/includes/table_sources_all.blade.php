@php
    use App\Models\NoiseSource;
    use App\Services\UrlForSorting;
    /** @var UrlForSorting $urlForSorting */
@endphp
<table class="table table-sm table-striped align-middle small text-center">
    <thead class="align-middle">
    <tr>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('name') }}">Наименование источника шума</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('mark') }}">Марка</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('distance') }}">Дистанция замера</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_31_5') }}">31.5</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_63') }}">63</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_125') }}">125</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_250') }}">250</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_500') }}">500</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_1000') }}">1000</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_2000') }}">2000</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_4000') }}">4000</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_8000') }}">8000</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_eq') }}">La экв</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('la_max') }}">La макс</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('foundation') }}">Обоснование</a></th>
        <th><a href="{{ $urlForSorting->generateUrlForSorting('remark') }}">Примечание</a></th>
        <th>Файл</th>
        @auth()
            <th>Сбор списка</th>
        @endauth
    </tr>
    </thead>
    <tbody>
    @if(! $paginator->hasPages())
        <tr>
            <td colspan="18" height="50"> К сожалению ничего не найдено, если вы найдете паспорт на данный источник можете добавить его в данную базу данных. </td>
        </tr>
    @endif

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
                                    title="Удалить из набора">-
                            </button>
                        </form>
                    @else
                        <form action="{{ route('noise.main.basket.store') }}" method="post">
                            @csrf
                            <button class="form-control-sm btn-primary"
                                    name="addSources"
                                    value="{{ $item->id }}"
                                    title="Добавить в набор">+
                            </button>
                        </form>
                    @endif
                </td>
            @endauth
        </tr>
    @endforeach
    </tbody>
</table>

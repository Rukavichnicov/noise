@php use App\Models\NoiseSource; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-striped small">
                            <thead>
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        @if($paginator->total() > $paginator->count())
            <br>
            <div class="d-flex justify-content-center">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>
@endsection



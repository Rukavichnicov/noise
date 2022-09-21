@php use App\Models\NoiseSource; @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered align-middle small text-center">
                            <thead class="table-light align-middle">
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
                                <th>Примечание</th>
                                <th>Изменить</th>
                                <th>Обоснование</th>
                                <th>Файл</th>
                                <th>Согласовать</th>
                                <th>Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                //отслеживание указателя в массиве
                                $i = 0;
                                // отслеживание момента для смены указателя
                                $j = 0;
                            @endphp
                            @foreach($noiseSourcesNotCheck as $item)
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
                                    <td> {{$item->remark}} </td>
                                    <td>
                                        <form action="{{ route('noise.admin.sources.edit', $item->id) }}" method="get">
                                            <button type="submit" class="form-control-sm">
                                                Изменить
                                            </button>
                                        </form>
                                    </td>
                                    @if($i === 0 && $j === 0)
                                        @include('noise.admin.includes.general_data')
                                    @endif
                                    @if($arrayRowSpan[$i] !== $j)
                                        @php
                                            $j++;
                                        @endphp
                                    @else
                                        @php
                                            $j = 1;
                                            $i++;
                                        @endphp
                                        @include('noise.admin.includes.general_data')
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



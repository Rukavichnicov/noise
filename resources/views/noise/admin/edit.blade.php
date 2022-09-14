@php     /** @var \App\Models\NoiseSource $item */ @endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        @include('noise.includes.result_messages')
        <form method="post" enctype="multipart/form-data"
              action="{{ route('noise.admin.sources.update', $item->id) }}">
            @csrf
            @method('PATCH')
            <p><b>Введите уточненные данные по отдельному источнику шума:</b></p>
            <label class="form-label" for="name">Наименование*: </label>
            <input class="form-control" type="text" id="name" name="name" maxlength="200" required value="{{ old('name', $item->name) }}">
            <label class="form-label" for="mark">Марка: </label>
            <input class="form-control" type="text" id="mark" name="mark" maxlength="200" value="{{ old('mark', $item->mark) }}">
            <label class="form-label" for="id_type_of_source">Тип источника: </label>
            <select class="form-select" id="id_type_of_source" name="id_type_of_source">
                @foreach($typeList as $typeOption)
                    <!-- TODO подумать как сделать лучше автоматическую генерацию свойств -->
                    <option value="{{ $typeOption->id }}"
                            @if($typeOption->id == old('id_type_of_source', $item->id_type_of_source))
                                selected
                        @endif>
                        {{ $typeOption->name }}
                    </option>
                @endforeach
            </select>
            <table class="table table-sm">
                <tr>
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
                </tr>
                <tr>
                    <td><input type="number" class="form-control" min="0"
                               max="50" step="0.01" name="distance" value="{{ old('distance', $item->distance) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_31_5" value="{{ old('la_31_5', $item->la_31_5) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_63" value="{{ old('la_63', $item->la_63) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_125" value="{{ old('la_125', $item->la_125) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_250" value="{{ old('la_250', $item->la_250) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_500" value="{{ old('la_500', $item->la_500) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_1000" value="{{ old('la_1000', $item->la_1000) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_2000" value="{{ old('la_2000', $item->la_2000) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_4000" value="{{ old('la_4000', $item->la_4000) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_8000" value="{{ old('la_8000', $item->la_8000) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_eq" value="{{ old('la_eq', $item->la_eq) }}"></td>
                    <td><input class="form-control" type="number" min="0"
                               max="200" step="0.01" name="la_max" value="{{ old('la_max', $item->la_max) }}"></td>
                </tr>
            </table>
            <label for="remark">Примечание: </label>
            <textarea class="form-control" id="remark" name="remark">{{ old('remark', $item->remark) }}</textarea>
            <label class="form-label">Обоснование шумовой характеристики: </label>
            <textarea class="form-control mb-2" name="foundation" maxlength="1000" required>{{ old('foundation', $item->fileNoiseSource->foundation) }}</textarea>
            <!-- TODO сделать рабочую ссылку -->
            <a href="/" target="_blank">Просмотр файла обоснования</a>
            <input type="submit" class="form-control mt-2 btn btn-primary" value="Сохранить изменения" name="submit">
        </form>
@endsection



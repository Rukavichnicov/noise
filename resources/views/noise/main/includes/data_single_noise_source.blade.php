<fieldset class="form-control mt-1">
    <legend class="form-label">Источник шума №{{ $i }}</legend>
    <label class="form-label" for="name_{{ $i }}">Наименование*: </label>
    <input class="form-control" type="text" id="name_{{ $i }}" name="name_{{ $i }}" value="{{ old('name_'.$i) }}" maxlength="200" required>
    <label class="form-label" for="mark_{{ $i }}">Марка: </label>
    <input class="form-control" type="text" id="mark_{{ $i }}" name="mark_{{ $i }}" value="{{ old('mark_'.$i) }}" maxlength="200">
    <label class="form-label" for="id_type_of_source_{{ $i }}">Тип источника: </label>
    <select class="form-select" id="id_type_of_source_{{ $i }}" name="id_type_of_source_{{ $i }}">
        @foreach($typeList as $typeOption)
            <!-- TODO подумать как сделать лучше автоматическую генерацию свойств -->
            <option value="{{ $typeOption->id }}"
                @if($typeOption->id == old('id_type_of_source_'.$i))
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
                       max="50" step="0.01" name="distance_{{ $i }}" value="{{ old('distance_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_31_5_{{ $i }}" value="{{ old('la_31_5_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_63_{{ $i }}" value="{{ old('la_63_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_125_{{ $i }}" value="{{ old('la_125_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_250_{{ $i }}" value="{{ old('la_250_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_500_{{ $i }}" value="{{ old('la_500_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_1000_{{ $i }}" value="{{ old('la_1000_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_2000_{{ $i }}" value="{{ old('la_2000_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_4000_{{ $i }}" value="{{ old('la_4000_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_8000_{{ $i }}" value="{{ old('la_8000_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_eq_{{ $i }}" value="{{ old('la_eq_'.$i) }}"></td>
            <td><input class="form-control" type="number" min="0"
                       max="200" step="0.01" name="la_max_{{ $i }}" value="{{ old('la_max_'.$i) }}"></td>
        </tr>
    </table>
    <label for="remark_{{ $i }}">Примечание: </label>
    <textarea class="form-control" id="remark_{{ $i }}" name="remark_{{ $i }}" maxlength="1000">{{ old('remark_'.$i) }}</textarea>
</fieldset>



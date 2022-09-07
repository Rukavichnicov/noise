<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoiseSourceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // TODO Переопределить сообщение если пользователь не авторизован (не 403 ошибку)
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Правила валидации значений полей при сохранении созданного объекта
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['count'] = 'numeric|min:1|max:30|required';
        $rules['foundation'] = 'string|min:1|max:1000|required';
        $rules['file_name'] = 'file|mimetypes:application/pdf|max:2048|required';
        if (is_numeric($this->request->get('count'))) {
            $count = $this->request->get('count') > 30 ? 30 : $this->request->get('count');
            for ($i = 1; $i <= $count; $i++) {
                $rules["name_$i"] = 'min:1|max:200|required';
                $rules["mark_$i"] = 'max:200|nullable';
                $rules["remark_$i"] = 'string|max:1000|nullable';
                $rules["id_type_of_source_$i"] = 'numeric|required|exists:type_noise_sources,id';
                $rules["distance_$i"] = 'numeric|min:0|max:50|nullable';
                $rules["la_31_5_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_63_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_125_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_250_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_500_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_1000_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_2000_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_4000_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_8000_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_eq_$i"] = 'numeric|min:0|max:200|nullable';
                $rules["la_max_$i"] = 'numeric|min:0|max:200|nullable';
            }
        }
        return $rules;
    }
    /**
     * Получение сообщение об ошибке на определённые правила проверки
     *
     * @return array
     */
    public function messages()
    {
        $octaves = ['31_5', '63', '125', '250', '500', '1000', '2000', '4000', '8000'];
        $messages = [];
        $messages['file_name.required'] = 'Загрузите файл для обоснования источника шума';
        $messages['file_name.file'] = 'Загрузите файл для обоснования источника шума';
        $messages['file_name.mimetypes'] = 'Загружаемый файл должен быть формата .pdf';
        $messages['file_name.max'] = 'Максимальный размер загружаемого файла :max кбайт';

        $messages['foundation.string'] = 'Обоснование источника шума должно быть строкой';
        $messages['foundation.min'] = 'Минимальная длина обоснования :min символ';
        $messages['foundation.max'] = 'Максимальная длина обоснования :max символов';
        $messages['foundation.required'] = 'Введите обоснование источника шума';

        $messages['count.numeric'] = 'Количество источников шума должно быть числом';
        $messages['count.min'] = 'Минимальное количество источников шума - :min.';
        $messages['count.max'] = 'Максимальное количество источников шума - :max.';
        $messages['count.required'] = 'Должно быть указано количество источников шума, вернитесь на предыдущую страницу чтобы его задать';

        if (is_numeric($this->request->get('count'))) {
            $count = $this->request->get('count') > 30 ? 30 : $this->request->get('count');
            for ($i = 1; $i <= $count; $i++) {
                $messages["name_$i.min"] = "Минимальная длина имени $i ИШ должна быть - :min символ";
                $messages["name_$i.max"] = "Максимальная длина имени $i ИШ должна быть - :max символов";
                $messages["name_$i.required"] = "Заполните имя для $i ИШ";

                $messages["mark_$i.max"] = "Максимальная длина марки $i ИШ должна быть - :max символов";

                $messages["remark_$i.max"] = "Максимальная длина примечания $i ИШ должна быть - :max символов";

                $messages["id_type_of_source_$i.numeric"] = "Тип источника указан не верно у $i ИШ";
                $messages["id_type_of_source_$i.required"] = "Должен быть указан тип источника у $i ИШ";
                $messages["id_type_of_source_$i.exists:type_noise_sources,id"] = "Тип источника указан не верно у $i ИШ";

                $messages["distance_$i.numeric"] = "Значение дистанции у $i ИШ должно быть числом";
                $messages["distance_$i.min"] = "Минимальное значение дистанции у $i ИШ должно быть не менее :min м.";
                $messages["distance_$i.max"] = "Максимальное значение дистанции у $i ИШ должно быть не более :max м.";

                foreach ($octaves as $octave) {
                    $messages["la_{$octave}_$i.numeric"] = "Значение шума в частоте $octave у $i ИШ должно быть числом";
                    $messages["la_{$octave}_$i.min"] = "Минимальное значение шума в частоте $octave у $i ИШ должно быть не менее :min дБА.";
                    $messages["la_{$octave}_$i.max"] = "Максимальное значение шума в частоте $octave у $i ИШ должно быть не более :max дБА.";
                }

                $messages["la_eq_$i.numeric"] = "Значение эквивалентного уровня шума у $i ИШ должно быть числом";
                $messages["la_eq_$i.min"] = "Минимальное значение эквивалентного уровня шума у $i ИШ должно быть не менее :min дБА.";
                $messages["la_eq_$i.max"] = "Максимальное значение эквивалентного уровня шума у $i ИШ должно быть не более :max дБА.";
                $messages["la_max_$i.numeric"] = "Значение максимального уровня шума у $i ИШ должно быть числом";
                $messages["la_max_$i.min"] = "Минимальное значение максимального уровня шума у $i ИШ должно быть не менее :min дБА.";
                $messages["la_max_$i.max"] = "Максимальное значение максимального уровня шума у $i ИШ должно быть не более :max дБА.";
            }
        }
        return $messages;
    }
}

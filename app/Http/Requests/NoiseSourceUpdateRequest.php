<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoiseSourceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
        $rules['foundation'] = 'min:1|max:1000|required';
        $rules["name"] = 'min:1|max:200|required';
        $rules["mark"] = 'max:200|nullable';
        $rules["remark"] = 'string|max:1000|nullable';
        $rules["id_type_of_source"] = 'numeric|required|exists:type_noise_sources,id';
        $rules["distance"] = 'numeric|min:0|max:50|nullable';
        $rules["la_31_5"] = 'numeric|min:0|max:200|nullable';
        $rules["la_63"] = 'numeric|min:0|max:200|nullable';
        $rules["la_125"] = 'numeric|min:0|max:200|nullable';
        $rules["la_250"] = 'numeric|min:0|max:200|nullable';
        $rules["la_500"] = 'numeric|min:0|max:200|nullable';
        $rules["la_1000"] = 'numeric|min:0|max:200|nullable';
        $rules["la_2000"] = 'numeric|min:0|max:200|nullable';
        $rules["la_4000"] = 'numeric|min:0|max:200|nullable';
        $rules["la_8000"] = 'numeric|min:0|max:200|nullable';
        $rules["la_eq"] = 'numeric|min:0|max:200|nullable';
        $rules["la_max"] = 'numeric|min:0|max:200|nullable';
        return $rules;
    }
    /**
     * Получение сообщения об ошибке на определённые правила проверки
     *
     * @return array
     */
    public function messages()
    {
        $octaves = ['31_5', '63', '125', '250', '500', '1000', '2000', '4000', '8000'];
        $messages = [];

        foreach ($octaves as $octave) {
            $messages["la_{$octave}.numeric"] = "Значение шума в частоте $octave у ИШ должно быть числом";
            $messages["la_{$octave}.min"] = "Минимальное значение шума в частоте $octave у ИШ должно быть не менее :min дБА.";
            $messages["la_{$octave}.max"] = "Максимальное значение шума в частоте $octave у ИШ должно быть не более :max дБА.";
        }
        $messages['foundation.min'] = 'Минимальная длина обоснования :min символ';
        $messages['foundation.max'] = 'Максимальная длина обоснования :max символов';
        $messages['foundation.required'] = 'Введите обоснование источника шума';

        $messages["name.min"] = "Минимальная длина имени ИШ должна быть - :min символ";
        $messages["name.max"] = "Максимальная длина имени ИШ должна быть - :max символов";
        $messages["name.required"] = "Заполните имя для ИШ";

        $messages["mark.max"] = "Максимальная длина марки ИШ должна быть - :max символов";

        $messages["remark.max"] = "Максимальная длина примечания ИШ должна быть - :max символов";

        $messages["id_type_of_source.numeric"] = "Тип источника указан не верно у ИШ";
        $messages["id_type_of_source.required"] = "Должен быть указан тип источника у ИШ";
        $messages["id_type_of_source.exists:type_noise_sources,id"] = "Тип источника указан не верно у ИШ";

        $messages["distance.numeric"] = "Значение дистанции у ИШ должно быть числом";
        $messages["distance.min"] = "Минимальное значение дистанции у ИШ должно быть не менее :min м.";
        $messages["distance.max"] = "Максимальное значение дистанции у ИШ должно быть не более :max м.";

        $messages["la_eq.numeric"] = "Значение эквивалентного уровня шума у ИШ должно быть числом";
        $messages["la_eq.min"] = "Минимальное значение эквивалентного уровня шума у ИШ должно быть не менее :min дБА.";
        $messages["la_eq.max"] = "Максимальное значение эквивалентного уровня шума у ИШ должно быть не более :max дБА.";
        $messages["la_max.numeric"] = "Значение максимального уровня шума у ИШ должно быть числом";
        $messages["la_max.min"] = "Минимальное значение максимального уровня шума у ИШ должно быть не менее :min дБА.";
        $messages["la_max.max"] = "Максимальное значение максимального уровня шума у ИШ должно быть не более :max дБА.";
        return $messages;
    }
}

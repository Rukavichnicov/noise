<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoiseSourceSearchRequest extends FormRequest
{
    /**
     * Правила валидации поисковой строки и сортировки
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'search' => ['required', 'min:2', 'max:200'],
            'direction' => [Rule::in(['ASC', 'DESC'])],
            'field' => [Rule::in(['name',
                                  'mark',
                                  'distance',
                                  'la_31_5',
                                  'la_63',
                                  'la_125',
                                  'la_250',
                                  'la_500',
                                  'la_1000',
                                  'la_2000',
                                  'la_4000',
                                  'la_8000',
                                  'la_eq',
                                  'la_max',
                                  'foundation',
                                  'remark',])],
        ];
    }

    /**
     * Получение сообщения об ошибке на определённые правила проверки
     *
     * @return array
     */
    public function messages()
    {
        $messages['search.required'] = 'Введите поисковый запрос';
        $messages['search.min'] = 'Минимальное количество символов в запросе - 2';
        $messages['search.max'] = 'Максимальное количество символов в запросе - 200';
        $messages['direction'] = 'Не верно указано направление сортировки';
        $messages['field'] = 'Не верно указано поле сортировки';

        return $messages;
    }
}

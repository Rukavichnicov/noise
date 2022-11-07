<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoiseSourceIndexRequest extends FormRequest
{
    /**
     * Правила валидации сортировки
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
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
    public function messages(): array
    {
        $messages['direction'] = 'Не верно указано направление сортировки';
        $messages['field'] = 'Не верно указано поле сортировки';
        return $messages;
    }
}

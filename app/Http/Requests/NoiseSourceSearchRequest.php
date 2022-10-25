<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoiseSourceSearchRequest extends FormRequest
{
    /**
     * Правила валидации поисковой строки
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'search' => ['required', 'min:2', 'max:200']
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

        return $messages;
    }
}

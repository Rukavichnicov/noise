<?php

namespace App\Http\Requests;

use App\Rules\ExistSourceInBasketUser;
use Illuminate\Foundation\Http\FormRequest;

class BasketCreateRequest extends FormRequest
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
        return [
            'addSources' => ['numeric', 'required', 'exists:noise_sources,id', new ExistSourceInBasketUser()]
        ];
    }
    /**
     * Получение сообщение об ошибке на определённые правила проверки
     *
     * @return array
     */
    public function messages()
    {
        $messages['addSources.required'] = 'Должен быть передан id ИШ';
        $messages['addSources.numeric'] = 'ID ИШ должен быть числом';
        $messages['addSources.exists'] = 'Такого номера нет в базе данных';
        return $messages;
    }
}

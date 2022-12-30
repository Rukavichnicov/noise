<?php

namespace App\Rules;

use App\Models\Basket;
use Illuminate\Contracts\Validation\Rule;

class ExistSourceInBasketUser implements Rule
{
    /**
     * @var Basket
     */
    private Basket $basket;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    /**
     * @param  string  $attribute
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = empty($this->basket->findInBasketUser($value));
        return $response;
    }

    /**
     * Получение сообщения об ошибке
     *
     * @return string
     */
    public function message()
    {
        return 'Источник шума с указанным ID уже добавлен в ваш список';
    }
}

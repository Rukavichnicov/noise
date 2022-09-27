<?php

namespace App\Rules;

use App\Repositories\BasketRepository;
use Illuminate\Contracts\Validation\Rule;

class ExistSourceInBasketUser implements Rule
{
    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->basketRepository = app(BasketRepository::class);
    }

    /**
     * @param  string  $attribute
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = empty($this->basketRepository->findInBasketUser($value));
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

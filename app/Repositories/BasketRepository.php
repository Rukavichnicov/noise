<?php

namespace App\Repositories;

use App\Models\Basket as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BasketRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

//    /**
//     * Получить данные источников шума в корзине пользователя с пагинацией
//     * @param int|null $countPage
//     * @return LengthAwarePaginator
//     */
//    public function getAllWithPaginate(int $countPage = null): LengthAwarePaginator
//    {
//        $columns = [
//            'id',
//            'id_user',
//            'id_noise_source'
//        ];
//
//        $result = $this->startConditions()
//                       ->select($columns)
//                       ->where('id_user', '=', Auth::id())
//                       ->orderBy('id', 'ASC')
//                       ->with(['noiseSource','fileNoiseSource'])
//                       ->paginate($countPage);
//        return $result;
//    }

    /**
     * Получить данные источников шума в корзине пользователя
     *
     * @return Collection
     */
    public function getAllSourcesInBasket(): Collection
    {
        $columns = [
            'id',
            'id_user',
            'id_noise_source'
        ];

        $result = $this->startConditions()
                       ->select($columns)
                       ->where('id_user', '=', Auth::id())
                       ->orderBy('id', 'ASC')
                       ->with(['noiseSource','fileNoiseSource'])
                       ->get();
        return $result;
    }

    /**
     * Получить данные источников шума в корзине пользователя без связей
     *
     * @return array
     */
    public function getAllSourcesInBasketWithoutRelation(): array
    {
        $columns = [
            'id_noise_source'
        ];
        $result = [];
        $baskets = $this->startConditions()
            ->select($columns)
            ->where('id_user', '=', Auth::id())
            ->get()
            ->all();
        foreach ($baskets as $item) {
            $result[] = $item->id_noise_source;
        }
        return $result;
    }

    /**
     * Пытается найти в корзине у пользователя источник шума
     */
    public function findInBasketUser($idNoiseSource): array
    {
        $result = $this->startConditions()
            ->select('id')
            ->where('id_user', '=', Auth::id())
            ->where('id_noise_source', '=', $idNoiseSource)
            ->get()
            ->toArray();
        return $result;
    }

    /**
     *
     * @param array $array
     * @return bool
     */
    public function saveOneModelBD(array $array): bool
    {
        $item = new Model();
        $item->id_user = Auth::id();
        $item->id_noise_source = (int) $array['addSources'];
        $item->created_at = now();
        return $item->save();
    }

    /**
     * @param int $idNoiseSource
     */
    public function deleteNoiseSourceInBasket($idNoiseSource)
    {
        $result = $this->startConditions()
                       ->where('id_user', '=', Auth::id())
                       ->where('id_noise_source', '=', $idNoiseSource)
                       ->delete();
        return $result;
    }
}

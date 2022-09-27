<?php

namespace App\Repositories;

use App\Models\Basket as Model;
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

    /**
     * Получить данные источников шума в корзине пользователя с пагинацией
     * @param int|null $countPage
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int $countPage = null): LengthAwarePaginator
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
                       ->with(['user:id','noiseSource'])
                       ->paginate($countPage);
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
     * @param int $id_file_sources
     */
    public function deleteNoiseSources(int $id_file_sources)
    {
        $item = Model::where('id_file_path', '=', $id_file_sources);
        $item->delete();
    }
}

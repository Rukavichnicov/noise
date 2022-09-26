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
     * Получить данные проверенных источников шума с пагинацией
     * @param int|null $countPage
     * @param bool $agreement
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
     *
     * @param int $i
     * @param array $array
     * @param int $idFileNoiseSource
     * @return bool
     */
    public function saveOneModelBD(int $i, array $array, int $idFileNoiseSource): bool
    {
        $item = new Model();
        $item->id_user = Auth::id();
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

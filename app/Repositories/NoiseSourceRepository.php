<?php

namespace App\Repositories;

use App\Models\NoiseSource as Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NoiseSourceRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }
    /**
     * @param int|null $countPage
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int $countPage = null)
    {
        $columns = [
            'id',
            'check_source',
            'name',
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
            'remark',
            'id_file_path',
            'id_type_of_source',
            'id_user',
        ];

        $result = $this->startConditions()
                       ->select($columns)
                       ->orderBy('id', 'ASC')
                       ->paginate($countPage);
        return $result;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function getEdit(int $id)
    {
        return $this->startConditions()->find($id);
    }
}

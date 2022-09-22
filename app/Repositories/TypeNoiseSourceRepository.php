<?php

namespace App\Repositories;

use App\Models\TypeNoiseSource as Model;
use Illuminate\Support\Collection;

class TypeNoiseSourceRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @return Collection
     */
    public function getListCategories(): Collection
    {
        $columns = implode(', ', ['id', 'name']);

        $TypeNoiseSource = new Model;
        $result = $TypeNoiseSource
            ->selectRaw($columns)
            ->toBase()
            ->get();
        return $result;
    }

    /**
     * @param int $id
     * @return ?Model
     */
    public function getEdit(int $id): ?Model
    {
        return $this->startConditions()->find($id);
    }
}

<?php

namespace App\Repositories;

use App\Models\NoiseSource as Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class NoiseSourceRepository extends CoreRepository
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
    public function getAllWithPaginate(int $countPage = null, bool $agreement = true): LengthAwarePaginator
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
            'remark',
            'id_file_path',
            'id_type_of_source',
            'id_user',
        ];

        $result = $this->startConditions()
                       ->select($columns)
                       ->where('check_source', '=', $agreement)
                       ->orderBy('id', 'ASC')
                       ->with(['fileNoiseSource:id,file_name,foundation'])
                       ->paginate($countPage);
        return $result;
    }

    /**
     *  Получить данные всех не проверенных источников шума
     */
    public function getAllNotCheck()
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
            'remark',
            'id_file_path',
            'id_type_of_source',
            'id_user',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->where('check_source', '=', false)
            ->orderBy('id', 'ASC')
            ->with(['fileNoiseSource:id,file_name,foundation'])
            ->get();
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
        $item->name = $array['name_' . $i];
        $item->mark = $array['mark_' . $i];
        $item->distance = $array['distance_' . $i];
        $item->la_31_5 = $array['la_31_5_' . $i];
        $item->la_63 = $array['la_63_' . $i];
        $item->la_125 = $array['la_125_' . $i];
        $item->la_250 = $array['la_250_' . $i];
        $item->la_500 = $array['la_500_' . $i];
        $item->la_1000 = $array['la_1000_' . $i];
        $item->la_2000 = $array['la_2000_' . $i];
        $item->la_4000 = $array['la_4000_' . $i];
        $item->la_8000 = $array['la_8000_' . $i];
        $item->la_eq = $array['la_eq_' . $i];
        $item->la_max = $array['la_max_' . $i];
        $item->remark = $array['remark_' . $i];
        $item->id_file_path = $idFileNoiseSource;
        $item->id_type_of_source = $array['id_type_of_source_' . $i];
        $item->id_user = Auth::id();
        return $item->save();
    }

    /**
     * @param int $id_file_sources
     */
    public function approveNoiseSources(int $id_file_sources)
    {
        $item = Model::where('id_file_path', '=', $id_file_sources);
        $item->update(['check_source' => true]);
    }

    /**
     * @param int $id_file_sources
     */
    public function deleteNoiseSources(int $id_file_sources)
    {
        $item = Model::where('id_file_path', '=', $id_file_sources);
        $item->delete();
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

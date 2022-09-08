<?php

namespace App\Repositories;

use App\Models\FileNoiseSource as Model;
use Illuminate\Http\UploadedFile;

class FileNoiseSourceRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     *
     * @param array|UploadedFile $downloadableFileNoiseSource
     * @return int
     */
    public function saveFileNoiseSourceBD(array|UploadedFile $downloadableFileNoiseSource): int
    {
        $fileNoiseSource = new Model();
        $fileNoiseSource->file_name = $downloadableFileNoiseSource->hashName();
        $fileNoiseSource->save();
        return $fileNoiseSource->id;
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

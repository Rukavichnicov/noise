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
    public function saveFileNoiseSourceBD(array|UploadedFile $downloadableFileNoiseSource, $arrayInput): int
    {
        $fileNoiseSource = new Model();
        $fileNoiseSource->file_name = $downloadableFileNoiseSource->hashName();
        $fileNoiseSource->foundation = $arrayInput['foundation'];
        $fileNoiseSource->save();
        return $fileNoiseSource->id;
    }

    /**
     * @param int $id_file_sources
     */
    public function deleteFileNoiseSources(int $id_file_sources)
    {
        Model::destroy($id_file_sources);
    }
    /**
     * @param int $id
     * @return Model
     */
    public function getFileNoiseSources(int $id): Model
    {
        return $this->startConditions()->find($id);
    }
}

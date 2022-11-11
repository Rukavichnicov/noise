<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Class NoiseSource
 *
 * @package App\Models\NoiseSource
 *
 * @property int $id
 * @property boolean $check_source
 * @property string $name
 * @property string $mark
 * @property float $distance
 * @property float $la_31_5
 * @property float $la_63
 * @property float $la_125
 * @property float $la_250
 * @property float $la_500
 * @property float $la_1000
 * @property float $la_2000
 * @property float $la_4000
 * @property float $la_8000
 * @property float $la_eq
 * @property float $la_max
 * @property string $remark
 * @property int $id_file_path
 * @property int $id_type_of_source
 * @property FileNoiseSource $fileNoiseSource
 * @property int $id_user
 * @property string $urlFileCheck
 * @property string $urlFileNotCheck
 *
 */
class NoiseSource extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
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

    /**
     * @return BelongsTo
     */
    public function fileNoiseSource(): BelongsTo
    {
        return $this->belongsTo(FileNoiseSource::class, 'id_file_path');
    }

    /**
     * Получить URL для открытия файла ИШ проверенного
     *
     * @return Attribute
     */
    public function urlFileCheck(): Attribute
    {
        $path = PATH_FILES_CHECK . $this->fileNoiseSource->file_name;
        $url = Storage::url($path);
        return Attribute::make(
            get: fn () => $url,
        );
    }

    /**
     * Получить URL для открытия файла ИШ не проверенного
     *
     * @return Attribute
     */
    public function urlFileNotCheck(): Attribute
    {
        $path = PATH_FILES_NOT_CHECK . $this->fileNoiseSource->file_name;
        $url = Storage::url($path);
        return Attribute::make(
            get: fn () => $url,
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Storage;

/**
 * Class NoiseSource
 *
 * @package App\Models\NoiseSource
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_noise_source
 * @property string $created_at
 * @property NoiseSource $noiseSource
 * @property FileNoiseSource $fileNoiseSource
 * @property string $urlFileCheck
 * @property User $user
 *
 */
class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_user',
        'id_noise_source',
        'created_at',
    ];

    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function noiseSource(): BelongsTo
    {
        return $this->belongsTo(NoiseSource::class, 'id_noise_source');
    }

    /**
     * @return hasOneThrough
     */
    public function fileNoiseSource(): hasOneThrough
    {
        return $this->hasOneThrough(
            FileNoiseSource::class,
            NoiseSource::class,
            'id',
            'id',
            'id_noise_source',
            'id_file_path');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Получить URL для открытия файла ИШ в корзине
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
}

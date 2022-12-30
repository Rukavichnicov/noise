<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Auth;
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
            'id_file_path'
        );
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
     * @return ?Attribute
     */
    public function urlFileCheck(): ?Attribute
    {
        if (isset($this->fileNoiseSource)) {
            $path = PATH_FILES_CHECK . $this->fileNoiseSource->file_name;
            $url = Storage::url($path);
            return Attribute::make(
                get: fn() => $url,
            );
        } else {
            $url = Storage::url(PATH_FILES_CHECK . 'file_not_found.pdf');
            return Attribute::make(
                get: fn() => $url,
            );
        }
    }

    /**
     * Получить данные источников шума в корзине пользователя с пагинацией
     * @param int $idUser
     * @param int $countPage
     * @return LengthAwarePaginator
     */
    public function getAllSourcesInBasketWithPaginate(int $idUser, int $countPage = 10): LengthAwarePaginator
    {
        $result = $this
            ->query()
            ->select(['id_user', 'id_noise_source'])
            ->where('id_user', '=', $idUser)
            ->with(['noiseSource', 'fileNoiseSource'])
            ->paginate($countPage);
        return $result;
    }

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

        $result = $this
            ->query()
            ->select($columns)
            ->where('id_user', '=', Auth::id())
            ->orderBy('id', 'ASC')
            ->with(['noiseSource', 'fileNoiseSource'])
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
        $baskets = $this
            ->query()
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
        $result = $this
            ->query()
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
        $this->id_user = Auth::id();
        $this->id_noise_source = (int)$array['addSources'];
        $this->created_at = now();
        return $this->save();
    }

    /**
     * @param int $idNoiseSource
     */
    public function deleteNoiseSourceInBasket($idNoiseSource)
    {
        $result = $this
            ->query()
            ->where('id_user', '=', Auth::id())
            ->where('id_noise_source', '=', $idNoiseSource)
            ->delete();
        return $result;
    }
}

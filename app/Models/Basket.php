<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

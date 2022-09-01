<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeNoiseSource
 *
 * @package App\Models\TypeNoiseSource
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 */
class TypeNoiseSource extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'slug',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property string $foundation
 * @property string $remark
 * @property int $id_file_path
 * @property int $id_type_of_source
 * @property int $id_user
 *
 */
class NoiseSource extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
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
}

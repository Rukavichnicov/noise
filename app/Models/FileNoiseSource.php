<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FileNoiseSource
 *
 * @package App\Models\FileNoiseSource
 *
 * @property int $id
 * @property string $file_name
 * @property string $foundation
 *
 */
class FileNoiseSource extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'file_name',
        'foundation',
    ];


}

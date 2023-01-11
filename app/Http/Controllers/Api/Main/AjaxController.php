<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Models\NoiseSource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AjaxController extends Controller
{
    /**
     * Отправка данных для одного объекта
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $noiseSource = NoiseSource::find($request->id_noise);
        if ($noiseSource === null) {
            return response([
                'noiseSource' => 'не найдено',
            ], 201);
        }

        return response([
            'noiseSource' => $noiseSource->name,
        ], 201);
    }

}

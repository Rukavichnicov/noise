<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Noise\Main\MainController;
use App\Models\Basket;
use App\Rules\ExistSourceInBasketUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BasketController extends MainController
{
    /**
     * Добавление источника шума в личный список пользователя
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $arrayInput = $request->validate([
            'addSources' => ['numeric', 'required', 'exists:noise_sources,id', new ExistSourceInBasketUser()],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $item = new Basket();
        $item->id_user = (int) $arrayInput['user_id'];
        $item->id_noise_source = (int) $arrayInput['addSources'];
        $item->created_at = now();
        $saved = $item->save();
        if ($saved) {
            return response([
                'successAdding' => true,
            ], 201);
        } else {
            return response([
                'msg' => 'Ошибка сохранения',
            ], 412);
        }
    }

    /**
     * Удаление источника шума из личного списка пользователя
     *
     * @param  int  $idNoiseSource
     * @return Response
     */
    public function destroy(int $idNoiseSource): Response
    {
        try {
            $result = Basket::query()
                ->where('id_user', '=', Auth::id())
                ->where('id_noise_source', '=', $idNoiseSource)
                ->delete();
            if($result === 0) {
                throw new Exception('Ошибка удаления файла из списка');
            }
            return response([
                'successDeleting' => true,
            ], 200);
        } catch (Exception $exception) {
            return response([
                'msg' => $exception->getMessage(),
            ], 412);
        }
    }
}

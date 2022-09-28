<?php

namespace App\Http\Controllers\Noise\Main;

use App\Http\Requests\BasketCreateRequest;
use App\Repositories\BasketRepository;
use App\Repositories\FileNoiseSourceRepository;
use App\Repositories\NoiseSourceRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BasketController extends MainController
{
    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    public function __construct()
    {
        parent::__construct();
        $this->basketRepository = app(BasketRepository::class);
    }
    /**
     *
     * @return View
     */
    public function index(): View
    {
        $paginator = $this->basketRepository->getAllWithPaginate(10);
        return view('noise.main.basket_sources', compact('paginator'));
    }

    /**
     *
     * @param  BasketCreateRequest  $request
     * @return RedirectResponse
     */
    public function store(BasketCreateRequest $request): RedirectResponse
    {
        try {
            $arrayInput = $request->input();
            $this->basketRepository->saveOneModelBD($arrayInput);
            return back();
        } catch (Exception $exception) {
            return back()->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    /**
     *
     * @param  int  $idNoiseSource
     * @return RedirectResponse
     */
    public function destroy($idNoiseSource)
    {
        try {
            $result = $this->basketRepository->deleteNoiseSourceInBasket($idNoiseSource);
            if($result === 0) {
                throw new Exception('Ошибка удаления файла из списка');
            }
            return back();
        } catch (Exception $exception) {
            return back()->withErrors(['msg' => $exception->getMessage()]);
        }
    }
}

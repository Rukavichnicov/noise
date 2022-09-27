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
     * @var NoiseSourceRepository
     */
    private NoiseSourceRepository $noiseSourceRepository;

    /**
     * @var FileNoiseSourceRepository
     */
    private FileNoiseSourceRepository $fileNoiseSourceRepository;

    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    public function __construct()
    {
        parent::__construct();
        $this->noiseSourceRepository = app(NoiseSourceRepository::class);
        $this->fileNoiseSourceRepository = app(FileNoiseSourceRepository::class);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd(__METHOD__);
    }
}

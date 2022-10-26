<?php

namespace App\Http\Controllers\Noise\Main;

use App\Http\Requests\BasketCreateRequest;
use App\Repositories\BasketRepository;
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
     * Показ источников шума в личном списке пользователя
     *
     * @return View
     */
    public function index(): View
    {
        $paginator = $this->basketRepository->getAllWithPaginate(10);
        return view('noise.main.basket_sources', compact('paginator'));
    }

    /**
     * Добавление источника шума в личный список пользователя
     *
     * @param  BasketCreateRequest  $request
     * @return RedirectResponse
     */
    public function store(BasketCreateRequest $request): RedirectResponse
    {
        $arrayInput = $request->input();
        $saved = $this->basketRepository->saveOneModelBD($arrayInput);
        if ($saved) {
            return back();
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    /**
     * Удаление источника шума из личного списка пользователя
     *
     * @param  int  $idNoiseSource
     * @return RedirectResponse
     */
    public function destroy(int $idNoiseSource): RedirectResponse
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

    /**
     * Скачать таблицу с выбранными ИШ
     *
     */
    public function downloadReport()
    {
        $data = $this->basketRepository->getAllSourcesInBasket();
        $array = [];
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        foreach ($data as $item) {
            $text = $section->addText($item->noiseSource->name);
            $text = $section->addText($item->noiseSource->mark);
            $text = $section->addText($item->noiseSource->distance);
            $text = $section->addText($item->noiseSource->la_31_5);
            $text = $section->addText($item->noiseSource->la_63);
            $text = $section->addText($item->noiseSource->la_125);
            $text = $section->addText($item->noiseSource->la_250);
            $text = $section->addText($item->noiseSource->la_500);
            $text = $section->addText($item->noiseSource->la_1000);
            $text = $section->addText($item->noiseSource->la_2000);
            $text = $section->addText($item->noiseSource->la_4000);
            $text = $section->addText($item->noiseSource->la_8000);
            $text = $section->addText($item->noiseSource->la_eq);
            $text = $section->addText($item->noiseSource->la_max);
            $text = $section->addText($item->fileNoiseSource->foundation);
            $text = $section->addText($item->noiseSource->remark);
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Appdividend.docx');
        return response()->download(public_path('Appdividend.docx'));
    }

    /**
     * Скачать архив с файлами обоснования источников шума
     *
     */
    public function downloadArchiveFile()
    {
        dd(__METHOD__);
    }
}

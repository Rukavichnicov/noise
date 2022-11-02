<?php

namespace App\Http\Controllers\Noise\Main;

use App\Contracts\ArchiveFileSourcesForUser;
use App\Contracts\ReportListSourcesForUser;
use App\Http\Requests\BasketCreateRequest;
use App\Repositories\BasketRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    public function downloadReport(ReportListSourcesForUser $report): BinaryFileResponse
    {
        $report->makeReport();
        $report->saveReport();
        $filePath = $report->getFileName();
        return response()->download(public_path($filePath))->deleteFileAfterSend();
    }

    /**
     * Скачать архив с файлами обоснования источников шума
     *
     * @throws Exception
     */
    public function downloadArchiveFile(ArchiveFileSourcesForUser $archive): BinaryFileResponse
    {
        $archive->makeArchive();
        $filePath = $archive->getFileName();
        return response()->download(public_path($filePath))->deleteFileAfterSend();
    }
}

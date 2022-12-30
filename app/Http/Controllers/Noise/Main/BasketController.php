<?php

namespace App\Http\Controllers\Noise\Main;

use App\Contracts\ArchiveFileSourcesForUser;
use App\Contracts\ReportListSourcesForUser;
use App\Http\Requests\BasketCreateRequest;
use App\Models\Basket;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BasketController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Показ источников шума в личном списке пользователя
     *
     * @param Basket $basket
     * @return View
     */
    public function index(Basket $basket): View
    {
        $idCurrentUser = Auth::id();
        $paginator = $basket->getAllSourcesInBasketWithPaginate($idCurrentUser);

        return view('noise.main.basket_sources', compact('paginator'));
    }

    /**
     * Добавление источника шума в личный список пользователя
     *
     * @param  BasketCreateRequest  $request
     * @return RedirectResponse
     */
    public function store(BasketCreateRequest $request, Basket $basket): RedirectResponse
    {
        $arrayInput = $request->validated();
        $saved = $basket->saveOneModelBD($arrayInput);
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
            $basket = new Basket();
            $result = $basket->deleteNoiseSourceInBasket($idNoiseSource);
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
    public function downloadArchiveFile(ArchiveFileSourcesForUser $archive): BinaryFileResponse|RedirectResponse
    {
        try {
            $archive->makeArchive();
            $filePath = $archive->getFileName();
            if (file_exists(public_path($filePath))) {
                return response()->download(public_path($filePath))->deleteFileAfterSend();
            } else {
                throw new Exception('Не удалось создать архив');
            }
        } catch (Exception $exception) {
            return back()->withErrors($exception->getMessage());
        }
    }
}

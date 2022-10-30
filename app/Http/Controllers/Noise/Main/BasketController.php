<?php

namespace App\Http\Controllers\Noise\Main;

use App\Http\Requests\BasketCreateRequest;
use App\Repositories\BasketRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
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

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $sectionStyle = array('orientation' => 'landscape',
            'marginLeft' => 600, //Левое поле равно 15 мм
            'marginRight' => 600,
            'marginTop' => 600,
        );
        $styleTable = [
            'borderSize' => 10,
            'align' => 'center'
        ];
        $cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
        $cellVCentered = array('valign' => 'center');

        $section = $phpWord->addSection($sectionStyle);
        $text = "Список источников шума";
        $fontStyle = array('name' => 'Times New Roman', 'size' => 12, 'color' => '000000', 'lang' => 'ru-RU');
        $parStyle = array('align' => 'center', 'spaceBefore' => 10);
        $section->addText(htmlspecialchars($text), $fontStyle, $parStyle);

        $fontStyleTable = array('name' => 'Times New Roman', 'size' => 10, 'color' => '000000', 'lang' => 'ru-RU', );
        $table = $section->addTable($styleTable);

        $arrHeaderTable = array("№ ИШ", "Наименование источника шума", "Марка", "Дистанция замера", "31.5", "63", "125",
            "250", "500", "1000", "2000", "4000", "8000", "La экв", "La макс", "Обоснование", "Примечание");

        $table->addRow(1000);
        foreach ($arrHeaderTable as $header) {
            $cell = $table->addCell(900, $cellVCentered)->addText($header, $fontStyleTable, $cellHCentered);
        }
        $numberSourcesInTable = 1;

        foreach ($data as $item) {
            $table->addRow(1000);
            $table->addCell(900, $cellVCentered)->addText($numberSourcesInTable, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->name, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->mark, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->distance, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_31_5, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_63, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_125, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_250, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_500, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_1000, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_2000, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_4000, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_8000, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_eq, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->la_max, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->fileNoiseSource->foundation, $fontStyleTable, $cellHCentered);
            $table->addCell(900, $cellVCentered)->addText($item->noiseSource->remark, $fontStyleTable, $cellHCentered);
            $numberSourcesInTable++;
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $fileName = Str::random(10) . '.docx';
        $objWriter->save($fileName);
        return response()->download(public_path($fileName))->deleteFileAfterSend();
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

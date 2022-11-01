<?php

namespace App\Services;

use App\Contracts\ReportListSourcesForUser;
use App\Repositories\BasketRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class ReportInWordListSourcesForUser implements ReportListSourcesForUser
{
    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    /**
     * @var PhpWord
     */
    private PhpWord $wordReport;

    /**
     * @var string
     */
    private string $fileName;

    /**
     * Стили для отображения документа
     * @var array
     */
    private array $sectionStyle = [
        'orientation' => 'landscape',
        'marginLeft' => 600, //Левое поле равно 15 мм
        'marginRight' => 600,
        'marginTop' => 600,
    ];

    /**
     * Стили для отображения таблицы
     * @var array
     */
    private array $styleTable = [
        'borderSize' => 10,
        'align' => 'center'
    ];

    /**
     * Отображение текста в ячейке по центру по горизонтали
     * @var array
     */
    private array $cellHCentered = ['alignment' => Jc::CENTER];

    /**
     * Отображение текста в ячейке по центру по вертикали
     * @var array
     */
    private array $cellVCentered = ['valign' => 'center'];

    /**
     * Описание шрифта в таблице
     * @var array
     */
    private array $fontStyleTable = ['name' => 'Times New Roman', 'size' => 10, 'color' => '000000', 'lang' => 'ru-RU',];

    /**
     * Описание шрифта заголовка
     * @var array
     */
    private array $fontStyleTitle = ['name' => 'Times New Roman', 'size' => 12, 'color' => '000000', 'lang' => 'ru-RU',];

    /**
     * Параметры отображения заголовка
     * @var array
     */
    private array $parStyleTitle = ['align' => 'center', 'spaceBefore' => 10,];

    public function __construct()
    {
        $this->basketRepository = app(BasketRepository::class);
        $this->wordReport = new PhpWord();
        $this->fileName = Str::random(10) . '.docx';
    }

    /**
     * @inheritDoc
     */
    public function getData(): Collection
    {
        return $this->basketRepository->getAllSourcesInBasket();
    }

    /**
     * @inheritDoc
     */
    public function makeReport()
    {
        $section = $this->wordReport->addSection($this->sectionStyle);
        $title = "Список источников шума";
        $section->addText($title, $this->fontStyleTitle, $this->parStyleTitle);

        $table = $section->addTable($this->styleTable);

        $arrHeadersTable = ["№ ИШ", "Наименование источника шума", "Марка", "Дистанция замера", "31.5", "63", "125",
            "250", "500", "1000", "2000", "4000", "8000", "La экв", "La макс", "Обоснование", "Примечание"];
        $table->addRow(1000);
        foreach ($arrHeadersTable as $header) {
            $table->addCell(900, $this->cellVCentered)->addText($header, $this->fontStyleTable, $this->cellHCentered);
        }

        $dataFromBasketUser = $this->getData();
        $numberSourcesInTable = 1;
        foreach ($dataFromBasketUser as $item) {
            $table->addRow(1000);
            $table->addCell(900, $this->cellVCentered)->addText($numberSourcesInTable, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->name, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->mark, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->distance, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_31_5, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_63, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_125, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_250, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_500, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_1000, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_2000, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_4000, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_8000, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_eq, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->la_max, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->fileNoiseSource->foundation, $this->fontStyleTable, $this->cellHCentered);
            $table->addCell(900, $this->cellVCentered)->addText($item->noiseSource->remark, $this->fontStyleTable, $this->cellHCentered);
            $numberSourcesInTable++;
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function saveReport()
    {
        $objWriter = IOFactory::createWriter($this->wordReport);
        $objWriter->save($this->fileName);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}

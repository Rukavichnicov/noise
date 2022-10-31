<?php

namespace App\Services;

use App\Contracts\ReportListSourcesForUser;
use App\Repositories\BasketRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
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
        $sectionStyle = ['orientation' => 'landscape',
            'marginLeft' => 600, //Левое поле равно 15 мм
            'marginRight' => 600,
            'marginTop' => 600,
        ];
        $styleTable = [
            'borderSize' => 10,
            'align' => 'center'
        ];
        $cellHCentered = ['alignment' => Jc::CENTER];
        $cellVCentered = ['valign' => 'center'];
        $fontStyleTable = ['name' => 'Times New Roman', 'size' => 10, 'color' => '000000', 'lang' => 'ru-RU', ];
        $fontStyle = ['name' => 'Times New Roman', 'size' => 12, 'color' => '000000', 'lang' => 'ru-RU'];
        $parStyle = ['align' => 'center', 'spaceBefore' => 10];

        $section = $this->wordReport->addSection($sectionStyle);
        $title = "Список источников шума";
        $section->addText($title, $fontStyle, $parStyle);

        $table = $section->addTable($styleTable);

        $arrHeaderTable = ["№ ИШ", "Наименование источника шума", "Марка", "Дистанция замера", "31.5", "63", "125",
            "250", "500", "1000", "2000", "4000", "8000", "La экв", "La макс", "Обоснование", "Примечание"];

        $table->addRow(1000);
        foreach ($arrHeaderTable as $header) {
            $table->addCell(900, $cellVCentered)->addText($header, $fontStyleTable, $cellHCentered);
        }
        $dataFromBasketUser = $this->getData();
        $numberSourcesInTable = 1;
        foreach ($dataFromBasketUser as $item) {
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
    }

    /**
     * @inheritDoc
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

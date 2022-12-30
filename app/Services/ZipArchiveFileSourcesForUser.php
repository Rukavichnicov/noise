<?php

namespace App\Services;

use App\Contracts\ArchiveFileSourcesForUser;
use App\Models\Basket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\Exception;
use ZipArchive;

class ZipArchiveFileSourcesForUser implements ArchiveFileSourcesForUser
{
    /**
     * @var Basket
     */
    private Basket $basket;

    /**
     * @var string
     */
    private string $fileName;

    /**
     * Массив имен файлов которые нужно добавить в массив
     * @var array
     */
    private array $arrayNameFile = [];

    /**
     * Массив имен обоснований на которые нужно будет заменить
     * @var array
     */
    private array $arrayNameFileFoundation = [];

    private ZipArchive $zip;

    public function __construct()
    {
        $this->basket = new Basket();
        $this->zip = new ZipArchive();
        $this->fileName = Str::random(10) . '.zip';
        $this->setArrayNameFile();
        $this->setArrayNameFileFoundation();
    }

    /**
     * @inheritDoc
     */
    public function getData(): Collection
    {
        return $this->basket->getAllSourcesInBasket();
    }

    /**
     * @inheritDoc
     */
    public function setArrayNameFile()
    {
        $data = $this->getData();
        foreach ($data as $item) {
            $this->arrayNameFile[] = $item->fileNoiseSource->file_name;
        }
        $this->arrayNameFile = array_unique($this->arrayNameFile);
    }

    /**
     * @inheritDoc
     */
    public function setArrayNameFileFoundation()
    {
        $data = $this->getData();
        $notAllowedSymbols = ['<', '>', ':', ';' , '/', '\\', '|', '?', '*'];
        foreach ($data as $item) {
            $newNameFile = mb_strimwidth($item->fileNoiseSource->foundation, 0, 200);
            $newNameFile = str_replace($notAllowedSymbols, " ", $newNameFile);
            $newNameFile = trim($newNameFile);
            $newNameFile = trim($newNameFile, '.');
            $newNameFile .= '.pdf';
            $this->arrayNameFileFoundation[] = $newNameFile;
        }
        $this->arrayNameFileFoundation = array_unique($this->arrayNameFileFoundation);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function makeArchive()
    {
        if ($this->zip->open(public_path($this->fileName), ZipArchive::CREATE)) {
            foreach ($this->arrayNameFile as $key => $nameFile) {
                $pathWithNameChecked = PATH_FILES_CHECK . $nameFile;
                if (Storage::disk()->exists($pathWithNameChecked)) {
                    $this->zip->addFile(Storage::path($pathWithNameChecked), $nameFile);
                    $this->zip->renameName($nameFile, $this->arrayNameFileFoundation[$key]);
                } else {
                    throw new Exception('Файл источника шума не найден');
                }
            }
        }
        $this->zip->close();
    }

    /**
     * @inheritDoc
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}

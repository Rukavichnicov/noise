<?php

namespace App\Contracts;

interface ArchiveFileSourcesForUser
{
    /**
     * Получить данные для создания архива
     */
    public function getData();

    /**
     * Обработать данные для получения списка файлов помещаемых в массив
     */
    public function setArrayNameFile();

    /**
     * Обработать данные для получения новых имен файлов на основе обоснования
     */
    public function setArrayNameFileFoundation();

    /**
     * Создать архив наполненный файлами и сохранить его
     */
    public function makeArchive();

    /**
     * Получить имя файла архива
     * @return string
     */
    public function getFileName(): string;
}

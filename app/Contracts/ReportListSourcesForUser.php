<?php

namespace App\Contracts;

interface ReportListSourcesForUser
{
    /**
     * Получить данные для формирования отчета
     */
    public function getData();

    /**
     * Создать отчет
     */
    public function makeReport();

    /**
     * Сохранить отчет
     */
    public function saveReport();

    /**
     * Получить имя файла отчёта
     * @return string
     */
    public function getFileName(): string;
}

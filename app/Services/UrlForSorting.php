<?php

namespace App\Services;

class UrlForSorting
{
    /**
     * Формирует URL для сортировки записей таблицы ИШ
     * @param string $fieldName
     * @return string
     */
    public function generateUrlForSorting(string $fieldName): string
    {
        $urlUrlForSorting = strpos(url()->full(), '?') ? url()->full() . '&' : url()->full() . '?';
        $urlUrlForSorting = $this->deletePreviousDirectionAndField($urlUrlForSorting);
        if (empty(request()->field) && $fieldName === 'name') {
            $sortingDirection = 'DESC';
        } else {
            $sortingDirection = (request()->field === $fieldName && request()->direction === 'ASC') ? 'DESC' : 'ASC';
        }
        $sortingDirection = 'direction=' . $sortingDirection;
        $fieldName = 'field=' . $fieldName;
        $urlUrlForSorting .= $fieldName . '&' . $sortingDirection;
        return $urlUrlForSorting;
    }

    /**
     * Очищает предыдущий URL от прошлых параметров сортировки
     * @param string $previousUrl
     * @return string
     */
    public function deletePreviousDirectionAndField(string $previousUrl): string
    {
        $hasSearchInUrl = strpos($previousUrl, 'search');
        if ($hasSearchInUrl) {
            $handledUrl = preg_replace("/&direction=.+&field=.+&search/", '&search', $previousUrl);
        } else {
            $handledUrl = preg_replace("/\?direction=.+&field=.+/", '?', $previousUrl);
        }
        return $handledUrl;
    }
}

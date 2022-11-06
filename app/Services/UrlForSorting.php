<?php

namespace App\Services;

class UrlForSorting
{
    public function generateUrlForSorting(string $fieldName): string
    {
        $urlUrlForSorting = strpos(url()->full(), '?') ? url()->full() . '&' : url()->full() . '?';
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
}

<?php

namespace App\Traits;

trait Paginatable
{
    protected $perPageMax = 100;

    public function getPerPage(): int
    {
        $perPage = request('per_page', $this->perPage);

        if (!filter_var($perPage, FILTER_VALIDATE_INT)) {
            return parent::getPerPage();
        }

        return max(1, min($this->perPageMax, (int) $perPage));
    }
}

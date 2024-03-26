<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService
{
    const DEFAULT_PER_PAGE_VALUE = 3;

    /**
     * @param $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public static function paginate($data, int $perPage): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();  //Get current page form url e.g. &page=6
        $currentPageResults = $data->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $data['results'] = new LengthAwarePaginator($currentPageResults, count($data), $perPage);

        return $data['results']->setPath(request()->url());
    }
}

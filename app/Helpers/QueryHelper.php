<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class QueryHelper
{
    /**
     * Global reusable search + pagination function
     * 
     * @param Builder $query
     * @param array $searchFields
     * @param int $perPage
     * @param string $inputName
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function globalSearchAndPaginate(Builder $query, array $searchFields = [], $perPage = 10, $inputName = 'search')
    {
        $search = request($inputName);

        if ($search && !empty($searchFields)) {
            $query->where(function ($q) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}

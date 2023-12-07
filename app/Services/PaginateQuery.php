<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * PAGINATE QUERY FOR REQUEST
 */
class PaginateQuery
{
    protected $queryFilter;
    protected $requestFilter;

    public function __construct(Builder $query, Request $request)
    {
        try {
            $this->queryFilter = $query;
            $this->requestFilter = $request;

            $this->getSkip();
            $this->getLimit();

            return $this->queryFilter;
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getSkip($value = 0)
    {
        $param = $this->requestFilter->get('!skip', $value);
        $this->queryFilter->skip($param);
        return $this->queryFilter;
    }

    protected function getLimit($value = 10)
    {
        $param = $this->requestFilter->get('!limit', $value);
        $this->queryFilter->limit($param);
        return $this->queryFilter;
    }
}

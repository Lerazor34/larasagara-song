<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * ORDER QUERY FOR REQUEST
 */
class OrderQuery
{
    protected $queryFilter;
    protected $requestFilter;

    public function __construct(Builder $query, Request $request,)
    {
        try {
            $this->queryFilter = $query;
            $this->requestFilter = $request;

            $this->getSort();

            return $this->queryFilter;
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getSort()
    {
        $orders = $this->requestFilter->only('!sort');


        foreach ($orders as $order) {
            foreach ($order as $name => $key) {
                $valueKey = $this->checkValueKey($key);
                $this->queryFilter->orderBy($name, $valueKey);
            }
        }
    }

    protected function checkValueKey($value)
    {
        if ($value == 1) {
            return 'asc';
        }
        return 'desc';
    }
}

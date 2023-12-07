<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * FILTER QUERY FOR REQUEST
 */
class FilterQuery
{
    protected $queryFilter;
    protected $requestFilter;
    protected $exceptParams;

    public function __construct(Builder $query, Request $request,)
    {
        try {
            $this->queryFilter = $query;
            $this->requestFilter = $request;
            $this->exceptParams = ['!limit', '!skip', '!sort'];

            $this->getWhere();

            return $this->queryFilter;
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getWhere()
    {
        $params = $this->requestFilter->except($this->exceptParams);
        foreach ($params as $nameFilter => $value) {
            $nameExploded = explode('!', $nameFilter);
            $name = $nameExploded[0];
            $operator = isset($nameExploded[1]) ? $nameExploded[1] : null;

            if (is_null($value)) continue;

            switch ($operator) {
                case 'like':
                    $this->operatorLike($name, $value);
                    break;

                case 'startLike':
                    $this->operatorStartLike($name, $value);
                    break;

                case 'endLike':
                    $this->operatorEndLike($name, $value);
                    break;

                case 'in':
                    $this->operatorIn($name, $value);
                    break;

                case 'nin':
                    $this->operatorNotIn($name, $value);
                    break;

                case 'ne':
                    $this->operatorNotEqual($name, $value);
                    break;

                case 'gt':
                    $this->operatorGreaterThan($name, $value);
                    break;

                case 'lt':
                    $this->operatorLesserThan($name, $value);
                    break;

                case 'gte':
                    $this->operatorGreaterThanEqual($name, $value);
                    break;

                case 'lte':
                    $this->operatorLesserThanEqual($name, $value);
                    break;

                default:
                    $this->operatorEqual($name, $value);
                    break;
            }
        }
    }

    protected function operatorEqual(String $name, String $value)
    {
        $this->queryFilter->where($name, $value);
    }

    protected function operatorLike(String $name, String $value)
    {
        $this->queryFilter->where($name, 'like', '%' . $value  . '%');
    }

    protected function operatorStartLike(String $name, String $value)
    {
        $this->queryFilter->where($name, 'like', $value . '%');
    }

    protected function operatorEndLike(String $name, String $value)
    {
        $this->queryFilter->where($name, 'like', '%' . $value);
    }

    protected function operatorIn(String $name, String $value)
    {
        $this->queryFilter->whereIn($name, $this->stringToArray($value));
    }

    protected function operatorNotIn(String $name, String $value)
    {
        $this->queryFilter->whereNotIn($name, $this->stringToArray($value));
    }

    protected function operatorNotEqual(String $name, String $value)
    {
        $this->queryFilter->whereNot($name, $value);
    }

    protected function operatorGreaterThan(String $name, String $value)
    {
        $this->queryFilter->where($name, '>', $value);
    }

    protected function operatorLesserThan(String $name, String $value)
    {
        $this->queryFilter->where($name, '<', $value);
    }

    protected function operatorGreaterThanEqual(String $name, String $value)
    {
        $this->queryFilter->where($name, '>=', $value);
    }

    protected function operatorLesserThanEqual(String $name, String $value)
    {
        $this->queryFilter->where($name, '<=', $value);
    }

    protected function stringToArray(String $value)
    {
        return explode(',', $value);
    }
}

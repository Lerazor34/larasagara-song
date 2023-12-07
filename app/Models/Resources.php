<?php

namespace App\Models;

use App\Services\FilterQuery;
use App\Services\PaginateQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Observers\Traits\Actorable;
use App\Services\OrderQuery;

class Resources extends Model
{
    use Actorable;

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

    use HasFactory;

    protected $guard_name = 'web';
    protected $forms = [];
    protected $reference = [];

    protected $rules = [];
    protected $fillable = [];

    public function getTableFields()
    {
        return Schema::getColumnListing($this->getTable());
    }

    public function validator($request, $event = 'create', $id = null)
    {
        $rules = $this->getValidationOf($event, $id);
        if ($event == 'patch') {
            foreach ($rules as $key => $value) {
                if (!$request->has($key)) {
                    unset($rules[$key]);
                }
            }
        }
        $validator = Validator::make($request->all(), $rules);
        return $validator;
    }

    public function getValidationOf($event = 'create', $id = null)
    {
        $rules = [];
        if ($event == 'patch') $event = 'update';
        foreach ($this->rules as $key => $validation) {
            $rule = $validation;
            if (is_array($validation) && isset($validation[$event])) {
                $rule = $validation[$event];
            }
            $rule = str_replace('{id}', $id, $rule);
            $rules[$key] = $rule;
        }
        return $rules;
    }

    public function checkTableExists($table_name)
    {
        return Schema::hasTable($table_name);
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getFields()
    {
        return $this->fillable;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function scopeStatusActive(Builder $query): void
    {
        $query->where('status', true);
    }

    public function scopeStatusInactive(Builder $query): void
    {
        $query->where('status', false);
    }

    public function scopePaginateFilter(Builder $query, Request $request): void
    {
        $query = new PaginateQuery($query, $request);
    }

    public function scopeFilter(Builder $query, Request $request): void
    {
        $query = new FilterQuery($query, $request);
    }

    public function scopeOrderFilter(Builder $query, Request $request): void
    {
        $query = new OrderQuery($query, $request);
    }
}

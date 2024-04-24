<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApplicationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->leftJoin('application_translations','applications.id','=','application_translations.application_id' )
            ->select('name','slug')
            ->where('name','!=','')
            ->where('locale','=',app()->getLocale())
            ->where('applications.online','=',1)
            ->where('application_translations.online','=',1)
            ->orderBy('name','asc');
    }
}

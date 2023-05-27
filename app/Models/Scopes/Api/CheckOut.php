<?php

namespace App\Models\Scopes\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CheckOut implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();
        $user_role = $user->getRoleNames()->first();
        if ($user_role == 'user') {
            $builder->where('user_id', $user->id);
        }
    }
}

<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CabangScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (!$user || $user->hasRole('super-admin') || !$user->cabang_id) {
            return;
        }

        if ($user->hasRole('admin-cabang')) {
            if (method_exists($model, 'kavling')) {
                $builder->whereHas('kavling', fn (Builder $q) => $q->where('cabang_id', $user->cabang_id));
            } elseif (method_exists($model, 'campaign')) {
                $builder->whereHas('campaign', fn (Builder $q) => $q->where('cabang_id', $user->cabang_id));
            } else {
                $builder->where('cabang_id', $user->cabang_id);
            }
        }
    }
}

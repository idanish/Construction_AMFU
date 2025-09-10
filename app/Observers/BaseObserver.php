<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class BaseObserver
{
    public function creating(Model $model)
    {
        $model->transaction_no = ($model->transaction_no ?? 0) + 1;
    }

    public function updating(Model $model)
    {
        $model->transaction_no = ($model->transaction_no ?? 0) + 1;
    }

    public function deleting(Model $model)
    {
        $model->transaction_no = ($model->transaction_no ?? 0) + 1;
    }
}

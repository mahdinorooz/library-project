<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ColumnRow extends Pivot
{
    protected $table = 'column_row';
    public $incrementing = true;

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
    public function row()
    {
        return $this->belongsTo(Row::class);
    }
}

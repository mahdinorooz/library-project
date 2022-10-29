<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Row extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];
    
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->updated_at = null;
        });
    }

    public function columns()
    {
        return $this->belongsToMany(Column::class)->using(ColumnRow::class)->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class CongressionalMinutes extends Model implements Sortable
{
    use HasFactory;
    use SoftDeletes;
    use SortableTrait;
    use DefaultDatetimeFormat;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
}

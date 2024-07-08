<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Traits\DefaultDatetimeFormat;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    use DefaultDatetimeFormat;

    public function member()
    {
        return $this->belongsTo('App\Models\Member');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}

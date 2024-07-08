<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Traits\DefaultDatetimeFormat;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    use DefaultDatetimeFormat;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class)->where('status', 1);
    }
}

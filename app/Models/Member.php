<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Traits\DefaultDatetimeFormat;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;
    use DefaultDatetimeFormat;

    /**
     * Get the user that owns the Member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class)->where('status', 1);
    }

    public function tags_all()
    {
        return $this->hasMany(Tag::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->where('status', 1);
    }

    public function categories_all()
    {
        return $this->hasMany(Category::class);
    }
}

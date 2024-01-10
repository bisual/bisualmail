<?php

namespace bisual\bisualmail\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
    use SoftDeletes;

    protected $fillable = ['id', 'title', 'send_date', 'content', 'filter'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'send_date'];

    protected $casts = [
        'filters' => 'json',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todos';

    protected $fillable = [
        'content', 'done', 'type', 'status'
    ];

    protected $casts = [
        'content' => 'string',
        'done' => 'integer',
        'type' => 'integer',
        'status' => 'integer',
    ];
}

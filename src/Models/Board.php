<?php

namespace OscarWeijman\FilamentKanban\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'color',
    ];


    protected $casts = [
        'id' => 'integer',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }
}

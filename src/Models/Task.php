<?php

namespace OscarWeijman\FilamentKanban\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'status',
        'order',
        'board_id',
    ];


    protected $casts = [
        'id' => 'integer',
        'board_id' => 'integer',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}

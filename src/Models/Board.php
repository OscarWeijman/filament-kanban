<?php

namespace OscarWeijman\FilamentKanban\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $color
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Board create(array $attributes = [])
 */
class Board extends Model
{
    use HasFactory;

    /**
     * @property string $color
     */
    public $fillable = [
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

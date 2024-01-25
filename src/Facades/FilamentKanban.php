<?php

namespace OscarWeijman\FilamentKanban\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \OscarWeijman\FilamentKanban\FilamentKanban
 */
class FilamentKanban extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \OscarWeijman\FilamentKanban\FilamentKanban::class;
    }
}

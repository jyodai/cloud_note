<?php

namespace App\Facades\Components;

use Illuminate\Support\Facades\Facade;

class Comp_File extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'comp_file';
    }
}

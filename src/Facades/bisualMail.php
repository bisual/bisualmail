<?php

namespace bisual\bisualmail\Facades;

use Illuminate\Support\Facades\Facade;

class bisualmail extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bisualmail';
    }
}

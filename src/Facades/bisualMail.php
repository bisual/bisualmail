<?php

namespace bisual\bisualMail\Facades;

use Illuminate\Support\Facades\Facade;

class bisualMail extends Facade
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

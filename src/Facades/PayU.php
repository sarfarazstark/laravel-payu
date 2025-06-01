<?php

namespace SarfarazStark\LaravelPayU\Facades;

use Illuminate\Support\Facades\Facade;

class PayU extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'payu';
    }
}

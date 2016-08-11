<?php

namespace AmazonHelper;

use Illuminate\Support\Facades\Facade;

class AmazonFacade extends Facade{
    protected static function getFacadeAccessor() { return 'amazon'; }
}
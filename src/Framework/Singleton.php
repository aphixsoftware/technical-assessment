<?php

namespace App\Framework;

use App\Features\Features;

class Singleton
{
    private static $instance;

    public $features;

    function __construct() {
        $this->features = new Features();
    }

    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new Singleton();
        }
        return self::$instance;
    }
}
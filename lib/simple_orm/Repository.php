<?php

namespace lib\simple_orm;

class Repository
{
    protected static $model = null;
    protected static $database = null;

    function __construct()
    {
        self::$database = $GLOBALS['DATABASE'];
    }
}

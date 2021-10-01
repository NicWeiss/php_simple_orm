<?php

namespace repository;

use lib\simple_orm\Repository;
use model\Notify;

class NotifyRepo extends Repository
{

    function __construct()
    {
        parent::__construct();
        self::$model = new Notify();
    }
}

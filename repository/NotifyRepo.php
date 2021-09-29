<?php

namespace repository;

use generic\Repository;
use model\Notify;

class NotifyRepo extends Repository
{

    function __construct()
    {
        parent::__construct();
        self::$model = new Notify();
    }

    public function create($model)
    {
        $db = self::$database;
        $db->create($model);
    }
}

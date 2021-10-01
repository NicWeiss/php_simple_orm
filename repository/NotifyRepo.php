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

    public function create($model)
    {
        $db = self::$database;
        $db->create($model);
    }

    public function update($model, $new_values = null)
    {
        $db = self::$database;
        $db->update($model->update($new_values));
    }
}

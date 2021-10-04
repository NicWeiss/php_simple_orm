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

    function get_by_user_id($id)
    {
        $db = self::$database;
        $query = $db->query();
        return $query->get(self::$model)->equal(['user_id' => $id])->fetch_all();
    }
}

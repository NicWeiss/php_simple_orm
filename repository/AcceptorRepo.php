<?php

namespace repository;

use lib\simple_orm\Repository;
use model\Acceptor;

class AcceptorRepo extends Repository
{

    function __construct()
    {
        parent::__construct();
        self::$model = new Acceptor();
    }

    function get_acceptor($user_id, $acceptor_id)
    {
        $db = self::$database;
        $query = $db->query();
        return $query->get(self::$model)->filter_by(['user_id', '=', $user_id], ['id', '=', $acceptor_id])->fetch();
    }

    function get_by_user_id($id)
    {
        $db = self::$database;
        $query = $db->query();
        return $query->get(self::$model)->filter_by(['user_id', '=', $id])->fetch_all();
    }
}

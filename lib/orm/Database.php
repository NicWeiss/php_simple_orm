<?php

namespace lib\orm;

use PDO;

class Database
{
    protected static $db = null;
    protected static $query = null;

    function __construct()
    {
        $dsn = 'mysql:dbname=notifier;host=127.0.0.1:56101';
        $user = 'root';
        $password = 'mysql';

        self::$db = new PDO($dsn, $user, $password);

        // $res = $db->query('select * from notify;')->fetchAll();
        // var_dump($res);
    }

    function create($model)
    {
        print_r($model);
    }
}

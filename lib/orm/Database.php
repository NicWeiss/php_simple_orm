<?php

namespace lib\orm;

use Exception;
use PDO;

class Database
{
    protected static $db = null;
    protected static $query = null;
    protected static $driver = null;

    function __construct()
    {
        //  Будет вынесено в конфиг
        $dsn = 'mysql:dbname=notifier;host=127.0.0.1:56101';
        $user = 'root';
        $password = 'mysql';

        self::$db = new PDO($dsn, $user, $password);
        self::$driver = self::$db->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    private function fetch($sql)
    {
        return self::$db->query($sql)->fetch();
    }

    private function execute($sql)
    {
        if (!self::$db->prepare($sql)->execute()) {
            throw new Exception("Can't execute SQL: $sql", 0);
        }

        return self::$db->lastInsertId();
    }

    function create($model)
    {
        $query = new Query();
        $query->create($model);
        $sql = $query->build_sql(self::$driver);

        print_r($sql);
        $res = $this->execute($sql);
        var_dump($res);
    }
}

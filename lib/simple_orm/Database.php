<?php

namespace lib\simple_orm;

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

    public function get_driver()
    {
        return self::$driver;
    }

    public function fetch($sql)
    {
        print_r($sql . PHP_EOL);
        return self::$db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetch_all($sql)
    {
        print_r($sql . PHP_EOL);
        return self::$db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute($sql)
    {
        print_r($sql . PHP_EOL);
        if (!self::$db->prepare($sql)->execute()) {
            throw new Exception("Can't execute SQL: $sql", 0);
        }
    }

    function create($model)
    {
        $query = new Query();
        $query->create($model);
        $sql = $query->build_sql(self::$driver);

        $this->execute($sql);
        $id = self::$db->lastInsertId();

        return $this->get($model, $id);
    }

    function get($model, $id)
    {
        $query = new Query();
        $query->get($model, $id);
        $sql = $query->build_sql(self::$driver);

        $result = $this->fetch($sql);
        return $model->update_attributes($result);
    }

    function update($model)
    {
        $query = new Query();
        $query->update($model);
        $sql = $query->build_sql(self::$driver);

        $this->execute($sql);

        //тут вернуть что-то
    }

    function delete($model)
    {
        $query = new Query();
        $query->delete($model);
        $sql = $query->build_sql(self::$driver);

        $res = $this->execute($sql);
        print_r($res);

        //тут вернуть что-то
    }

    function query()
    {
        return new Query($this);
    }
}

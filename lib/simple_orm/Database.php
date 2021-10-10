<?php

namespace lib\simple_orm;

use Exception;
use PDO;
use lib\simple_orm\Relation;

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

    public function fetch($model, $sql, $excluded_relation = '')
    {
        print_r($sql . PHP_EOL);

        $result = self::$db->query($sql)->fetch(PDO::FETCH_ASSOC);
        $model->update_attributes($result);

        $relation_resolver = new Relation($this, $model, $excluded_relation);
        $model = $relation_resolver->get();

        return $model;
    }

    public function fetch_all($model, $sql, $excluded_relation = '')
    {
        print_r($sql . PHP_EOL);

        $result = self::$db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        $class_name = $model->get_class_name();

        foreach ($result as $item) {
            $model = new $class_name();
            $model->update_attributes($item);

            $relation_resolver = new Relation($this, $model, $excluded_relation);
            $model = $relation_resolver->get();
            array_push($models, $model);
        }

        return $models;
    }

    public function execute($model, $sql, $operation_type = '', $excluded_relation = '')
    {
        print_r($sql . PHP_EOL);

        if (!self::$db->prepare($sql)->execute()) {
            throw new Exception("Can't execute SQL: $sql", 0);
        }

        $last_inserted = self::$db->lastInsertId();

        $relation_resolver = new Relation($this, $model, $excluded_relation);
        $relation_resolver->operate($operation_type, $last_inserted);

        if ($operation_type == 'create') {
            return $last_inserted;
        }
    }

    function query()
    {
        return new Query($this);
    }
}

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
        // $model = $relation_resolver->apply_relations();
        return $model;
        // $model_with_relations = $relation_resolver->get();

        // return $model_with_relations;
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

        return $this->fetch($model, $sql);
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

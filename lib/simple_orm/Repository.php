<?php

namespace lib\simple_orm;

class Repository
{
    protected static $model = null;
    protected static $database = null;

    function __construct()
    {
        self::$database = $GLOBALS['DATABASE'];
    }

    public function create($model)
    {
        $db = self::$database;
        $db->create($model);
    }

    public function get($id)
    {
        $db = self::$database;
        $class = get_class(self::$model);
        return $db->get(new $class, $id);
    }

    public function update($model, $new_values)
    {
        $db = self::$database;
        $db->update($model->update_attributes($new_values));
    }

    public function delete($model)
    {
        $db = self::$database;
        $db->delete($model);
    }
}

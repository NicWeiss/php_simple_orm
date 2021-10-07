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
        $query = $db->query();
        $model_id = $query->create($model)->execute();

        return $this->get($model_id);
    }

    public function get($id)
    {
        $db = self::$database;
        $class = get_class(self::$model);
        $query = $db->query();
        return $query->get(new $class)->filter_by(['id', '=', $id])->fetch();
    }

    public function update($model, $new_values)
    {
        $db = self::$database;
        $query = $db->query();

        return $query->update($model->update_attributes($new_values))->execute();
    }

    public function delete($model)
    {
        $db = self::$database;
        $query = $db->query();

        return $query->delete($model)->execute();
    }
}

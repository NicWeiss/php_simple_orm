<?php

namespace lib\simple_orm;

use lib\simple_orm\builder\MysqlBuilder;

class Query
{

    function __construct()
    {
        $this->store = [];
    }

    public function create($model)
    {
        $this->add_operation(array('insert' => $model));
    }

    public function get($model, $id = null)
    {
        $this->add_operation(array('select' => ['model' => $model, 'id' => $id]));
    }

    public function update($model)
    {
        $this->add_operation(array('update' => $model));
    }

    public function delete($model)
    {
        $this->add_operation(array('delete' => $model));
    }

    private function add_operation($operation)
    {
        array_push($this->store, $operation);
    }

    public function build_sql($driver)
    {
        if ($driver == 'mysql') {
            $builder = new MysqlBuilder();
        }

        return $builder->build($this->store);
    }
}

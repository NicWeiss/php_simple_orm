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
        $this->add_operation(array('create' => $model));
    }

    public function update($model)
    {
        $this->add_operation(array('update' => $model));
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

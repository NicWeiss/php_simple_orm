<?php

namespace lib\orm;

use lib\orm\builder\MysqlBuilder;

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

<?php

namespace lib\orm;


class Query
{

    function __construct($model)
    {
        $this->store = [];
        $this->model = $model;
    }

    public function select($filter = 'all')
    {
        $this->add_operation(array('select' => $filter));
    }

    private function add_operation($operation)
    {
        $keys = $this->model->get_model_properties();
        print_r($keys);
        array_push($this->store, $operation);
        print_r($this->store);
    }

    public function build()
    {
        $table = get_class($this);
        print_r($table);
    }
}

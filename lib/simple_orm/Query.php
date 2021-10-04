<?php

namespace lib\simple_orm;

use lib\simple_orm\builder\MysqlBuilder;

class Query
{

    function __construct($db = null)
    {
        $this->store = [];
        $this->db = $db;
    }

    public function create($model)
    {
        $this->add_operation(array('insert' => $model));
    }

    public function get($model, $id = null)
    {
        $this->add_operation(array('select' => ['model' => $model, 'id' => $id]));
        return $this;
    }

    public function update($model)
    {
        $this->add_operation(array('update' => $model));
        return $this;
    }

    public function delete($model)
    {
        $this->add_operation(array('delete' => $model));
        return $this;
    }

    public function equal($params)
    {
        $this->add_operation(array('where_processor' => ['=', $params]));
        return $this;
    }

    public function like($params)
    {
        $this->add_operation(array('where_processor' => ['LIKE', $params]));
        return $this;
    }

    public function more($params)
    {
        $this->add_operation(array('where_processor' => ['>', $params]));
        return $this;
    }

    public function less($params)
    {
        $this->add_operation(array('where_processor' => ['<', $params]));
        return $this;
    }

    private function add_operation($operation)
    {
        array_push($this->store, $operation);
    }

    public function fetch()
    {
        $sql = $this->build_sql($this->db->get_driver());
        return $this->db->fetch($sql);
    }

    public function fetch_all()
    {
        $sql = $this->build_sql($this->db->get_driver());
        return $this->db->fetch_all($sql);
    }

    public function build_sql($driver)
    {
        if ($driver == 'mysql') {
            $builder = new MysqlBuilder();
        }

        return $builder->build($this->store);
    }
}

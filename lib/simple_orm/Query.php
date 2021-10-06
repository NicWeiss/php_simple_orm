<?php

namespace lib\simple_orm;

use Exception;
use lib\simple_orm\builder\MysqlBuilder;

class Query
{
    const ALLOWED_COMPARSION_OPERATORS = ['>', '>=', '<', '<>', '!=', '<=', '<=>', '=', 'IS', 'IS NOT', 'IS NOT NULL', 'IS NULL', 'LIKE', 'NOT LIKE'];

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
        $this->model = $model;
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

    public function filter_by()
    {
        $filters = func_get_args();

        foreach ($filters as $filter) {
            $operator = $filter[1];

            if (in_array(strtoupper($operator), self::ALLOWED_COMPARSION_OPERATORS)) {
                $first_operand = addslashes($filter[0]);
                $second_operand = addslashes($filter[2]);

                $this->add_operation(array('where_processor' => [$first_operand, $operator, $second_operand]));
            } else {
                throw new Exception("Operator not allowed $operator. Allowed operators is " . implode(self::ALLOWED_COMPARSION_OPERATORS), 0);
            }
        }

        return $this;
    }

    private function add_operation($operation)
    {
        array_push($this->store, $operation);
    }

    public function fetch($excluded_relation = '')
    {
        $sql = $this->build_sql($this->db->get_driver());
        return $this->db->fetch($this->model, $sql, $excluded_relation);
    }

    public function fetch_all($excluded_relation = '')
    {
        $sql = $this->build_sql($this->db->get_driver());
        return $this->db->fetch_all($this->model, $sql, $excluded_relation);
    }

    public function build_sql($driver)
    {
        if ($driver == 'mysql') {
            $builder = new MysqlBuilder();
        }

        return $builder->build($this->store);
    }
}

<?php

namespace lib\simple_orm\builder;


class MysqlBuilder
{
    protected $sql = '';
    protected $first_operand = '';
    protected $selectors = '';
    protected $second_operand = '';
    protected $table = '';
    protected $third_operand = '';
    protected $values = '';
    protected $where_section = '';

    public function build($operations)
    {
        foreach ($operations as $operation) {
            foreach ($operation as $key => $value) {
                $this->$key($value);
            }
        }

        if (!$this->sql) {
            $this->sql = $this->first_operand . ' '
                . $this->selectors . ' '
                . $this->second_operand . ' '
                . $this->table . ' '
                . $this->third_operand . ' '
                . $this->values . ' '
                . $this->where_section . ' '
                . ';';
        }
        return $this->sql;
    }

    private function type_wrapper($value)
    {
        $type = gettype($value);

        if ($type == 'string') {
            return "'$value'";
        } else if ($type == 'NULL') {
            return "null";
        }

        return $value;
    }

    private function insert($model)
    {
        $this->table = strtolower($model->get_model_name());
        $properties = $model->get_model_properties();
        $fields = '';
        $values = '';

        foreach ($properties as $property) {
            if ($fields) {
                $fields .= ", ";
                $values .= ", ";
            }

            $fields .= "`$property`";
            $value = $this->type_wrapper($model->$property);
            $values .= "$value";
        }

        $this->sql = "INSERT INTO  " . $this->table . " ($fields) VALUES ($values);";
    }

    private function select($params)
    {
        $this->table = $this->prepare_table_name($params['model']->get_model_name());
        $properties = $params['model']->get_model_properties();

        $this->first_operand = 'SELECT';
        $this->second_operand = 'FROM';

        if ($params['id']) {
            $this->where_section = 'WHERE `id`=' . $params['id'];
        }

        foreach ($properties as $property) {
            if ($this->selectors) {
                $this->selectors .= ", ";
            }

            $this->selectors .= "`$property`";
        }
    }

    private function update($model)
    {
        $this->table = $this->prepare_table_name($model->get_model_name());
        $properties = $model->get_model_properties();

        $this->first_operand = 'UPDATE';
        $this->third_operand = 'SET';
        $this->where_section = 'WHERE `id`=' . $model->id;

        foreach ($properties as $property) {
            if ($this->values) {
                $this->values .= ", ";
            }

            $value = $this->type_wrapper($model->$property);
            $this->values .= "`$property`=$value";
        }
    }

    private function delete($model)
    {
        $this->table = $this->prepare_table_name($model->get_model_name());

        $this->first_operand = 'DELETE FROM';
        $this->where_section = 'WHERE `id`=' . $model->id;
    }

    private function where_processor($filter)
    {
        if (!$this->where_section) {
            $this->where_section = 'WHERE ';
        } else {
            $this->where_section .= " AND ";
        }
        $value = $this->type_wrapper($filter[2]);
        $this->where_section .= "`$filter[0]` $filter[1] $value";
    }


    private function prepare_table_name($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}

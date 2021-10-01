<?php

namespace lib\simple_orm\builder;


class MysqlBuilder
{
    protected static $sql = '';
    protected static $first_operand = '';
    protected static $table = '';
    protected static $second_operand = '';
    protected static $values = '';
    protected static $where_section = '';

    public function build($operations)
    {
        foreach ($operations as $operation) {
            foreach ($operation as $key => $value) {
                $this->$key($value);
            }
        }

        if (!self::$sql) {
            self::$sql = self::$first_operand . ' '
                . self::$table . ' '
                . self::$second_operand . ' '
                . self::$values . ' '
                . self::$where_section . ' '
                . ';';
        }
        return self::$sql;
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

    private function create($model)
    {
        self::$table = strtolower($model->get_model_name());
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

        self::$sql = "INSERT INTO  " . self::$table . " ($fields) VALUES ($values);";
    }

    private function update($model)
    {
        self::$table = strtolower($model->get_model_name());
        $properties = $model->get_model_properties();

        self::$first_operand = 'UPDATE';
        self::$second_operand = 'SET';
        self::$where_section = 'WHERE `id`=' . $model->id;

        foreach ($properties as $property) {
            if (self::$values) {
                self::$values .= ", ";
            }

            $value = $this->type_wrapper($model->$property);
            self::$values .= "`$property`=$value";
        }
    }
}

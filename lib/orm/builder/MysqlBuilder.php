<?php

namespace lib\orm\builder;


class MysqlBuilder
{
    protected static $sql = '';

    public function build($operations)
    {
        foreach ($operations as $operation) {
            foreach ($operation as $key => $value) {
                $this->$key($value);
            }
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
        $table = strtolower($model->get_model_name());
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

        self::$sql = "INSERT INTO  $table ($fields) VALUES ($values)q;";
    }
}

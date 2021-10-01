<?php

namespace lib\simple_orm;

use Exception;

class Model
{
    function __construct($values = null)
    {
        if ($values) {
            $this->update($values);
        }
    }

    public function update($values)
    {
        $properties = $this->get_model_properties();
        foreach ($values as $key => $value) {
            if (in_array($key, $properties)) {
                $this->$key = $value;
            } else {
                $model_name = $this->get_model_name();
                throw new Exception("Model $model_name does not have key: $key", 0);
            }
        }

        return $this;
    }

    public function get_model_properties()
    {
        return array_keys(get_class_vars(get_class($this)));
    }

    public function get_model_name()
    {
        return substr(strrchr(get_class($this), "\\"), 1);
    }
}

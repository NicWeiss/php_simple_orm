<?php

namespace generic;

use Exception;

class Model
{
    function __construct($array = null)
    {
        if (!$array) {
            return;
        }

        $properties = $this->get_model_properties();
        foreach ($array as $key => $value) {
            if (in_array($key, $properties)) {
                $this->$key = $value;
            } else {
                $model_name = $this->get_model_name();
                throw new Exception("Model $model_name does not have key: $key", 0);
            }
        }
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

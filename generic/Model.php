<?php

namespace generic;


class Model
{
    function __construct()
    {
        var_dump($this->get_model_properties());
    }

    public function get_model_properties()
    {
        return array_keys(get_class_vars(get_class($this)));
    }
}

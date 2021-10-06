<?php

namespace lib\simple_orm;

use Exception;
use lib\simple_orm\Relation;

class Model
{
    function __construct($arrtibutes = null)
    {
        $this->define_relations();

        if ($arrtibutes) {
            $this->update_attributes($arrtibutes);
        }
    }

    public function define_relations()
    {
        $this->relarions = [];
    }

    public function update_attributes($arrtibutes)
    {
        $properties = $this->get_model_properties(true);
        foreach ($arrtibutes as $key => $value) {
            if (in_array($key, $properties)) {
                $this->$key = $value;
            } else {
                $model_name = $this->get_model_name();
                throw new Exception("Model $model_name does not have key: $key", 0);
            }
        }

        return $this;
    }

    public function get_model_properties($with_relations = false)
    {
        $all_properties = array_keys(get_class_vars(get_class($this)));

        if ($with_relations) {
            return $all_properties;
        }

        return array_filter($all_properties, array($this, "exclude_relations"));
    }

    public function get_relations()
    {
        return $this->relarions;
    }

    public function exclude_relations($var)
    {
        return !in_array($var, array_keys($this->relarions));
    }

    public function get_model_name()
    {
        return substr(strrchr(get_class($this), "\\"), 1);
    }

    public function get_class_name()
    {
        return get_class($this);
    }
}

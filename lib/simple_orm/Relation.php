<?php

namespace lib\simple_orm;

use Exception;

class Relation
{
    const RELATION_TYPES = ['one_to_many', 'one_to_one'];

    function __construct($db, $model, $excluded_relation = '')
    {
        $this->db = $db;
        $this->model = $model;
        $relations = $this->model->get_relations();

        if (is_array($relations)) {
            foreach ($relations as $name => $relation) {
                $type = $relation[0];

                if (
                    in_array($type, self::RELATION_TYPES) and
                    $relation[1] != $excluded_relation
                ) {
                    $this->$type($name, $relation);
                }
            }
        }

        return $this;
    }

    private function one_to_many($name, $relation)
    {
        $relation_model = new $relation[1];
        $origin_prop = $relation[2];
        $relation_field = $relation[3];

        $query = $this->db->query();
        $relation_models = $query->get($relation_model)->filter_by([$relation_field, '=', $this->model->$origin_prop])->fetch_all($this->model->get_class_name());

        $models = [];

        foreach ($relation_models as $item) {
            $relation_names = array_keys($item->get_relations());

            foreach ($relation_names as $relation_name) {
                if ($item->$relation_name) {
                    array_push($models, $item->$relation_name);
                }
            }
        }

        $this->model->update_attributes([$name => $models]);
    }

    private function one_to_one($name, $relation)
    {
        $relation_model = new $relation[1];
        $origin_prop = $relation[2];
        $relation_field = $relation[3];

        $query = $this->db->query();
        $relation_model = $query->get($relation_model)->filter_by([$relation_field, '=', $this->model->$origin_prop])->fetch($this->model->get_class_name());

        $this->model->update_attributes([$name => $relation_model]);
    }

    public function get()
    {
        return $this->model;
    }
}

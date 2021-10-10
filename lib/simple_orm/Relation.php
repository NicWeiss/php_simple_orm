<?php

namespace lib\simple_orm;

use Exception;

class Relation
{
    const RELATION_TYPES = ['many_to_many', 'one_to_one', 'one_to_many'];

    function __construct($db, $model, $excluded_relation = '')
    {
        $this->db = $db;
        $this->model = $model;
        $this->excluded_relation = $excluded_relation;
    }

    public function get()
    {
        $relations = $this->model->get_relations();

        if (is_array($relations)) {
            foreach ($relations as $name => $relation) {
                $type = $relation[0];

                if (
                    in_array($type, self::RELATION_TYPES) and
                    $relation[1] != $this->excluded_relation
                ) {
                    $classname = "get_$type";
                    $this->$classname($name, $relation);
                }
            }
        }

        return $this->model;
    }

    public function operate($operation_type, $last_inserted = null)
    {
        $relations = $this->model->get_relations();

        if (is_array($relations)) {
            foreach ($relations as $name => $relation) {
                $type = $relation[0];

                if (
                    in_array($type, self::RELATION_TYPES) and
                    $relation[1] != $this->excluded_relation
                ) {
                    $down_line = "_";
                    $classname = "$operation_type$down_line$type";
                    $this->$classname($name, $relation, $last_inserted);
                }
            }
        }

        return $this->model;
    }

    private function get_many_to_many($name, $relation)
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

    private function get_one_to_one($name, $relation)
    {
        $relation_model = new $relation[1];
        $origin_prop = $relation[2];
        $relation_field = $relation[3];

        $query = $this->db->query();
        $relation_model = $query->get($relation_model)->filter_by([$relation_field, '=', $this->model->$origin_prop])->fetch($this->model->get_class_name());

        $this->model->update_attributes([$name => $relation_model]);
    }

    private function get_one_to_many($name, $relation)
    {
        var_dump('get_one_to_many');
    }

    private function create_one_to_one($name, $relation)
    {
        var_dump('create_one_to_one');
    }

    private function create_one_to_many($name, $relation)
    {
        var_dump('create_one_to_many');
    }

    private function create_many_to_many($name, $relation, $last_inserted)
    {
        $new_relations = $this->model->$name;

        if ($new_relations == null) {
            return;
        }
        if (count($new_relations) == 0) {
            return;
        }

        $many_to_many_relation_model = new $relation[1]();
        $keys_by_model = [];

        foreach ($many_to_many_relation_model->get_relations() as $key => $value) {
            $keys_by_model[$value[1]] = $value[2];
        }

        foreach ($new_relations as $item) {
            if ($item->id == null) {
                continue;
            }

            $update_data = [
                $keys_by_model[$item->get_class_name()] => $item->id,
                $keys_by_model[$this->model->get_class_name()] => $last_inserted,
            ];

            $many_to_many_relation_model->update_attributes($update_data);

            $query = $this->db->query();
            $query->create($many_to_many_relation_model)->execute();
        }
    }

    private function update_one_to_one($name, $relation)
    {
        var_dump('update_one_to_one');
    }

    private function update_one_to_many($name, $relation)
    {
        var_dump('update_one_to_many');
    }

    private function update_many_to_many($name, $relation)
    {
        $new_relations = $this->model->$name;

        $query = $this->db->query();
        $classname = $this->model->get_class_name();
        $old_model = $query->get(new $classname)->filter_by(['id', '=', $this->model->id])->fetch();

        $old_relations = $old_model->$name;

        if ($new_relations == null) {
            $new_relations = [];
        }
        if ($old_relations == null) {
            $old_relations = [];
        }

        $many_to_many_relation_model = new $relation[1]();
        $keys_by_model = [];

        foreach ($many_to_many_relation_model->get_relations() as $key => $value) {
            $keys_by_model[$value[1]] = $value[2];
        }

        $new_relations_ids = $this->get_ids($new_relations);
        $old_relations_ids = $this->get_ids($old_relations);

        foreach ($new_relations as $item) {
            if (!in_array($item->id, $old_relations_ids)) {
                $update_data = [
                    $keys_by_model[$item->get_class_name()] => $item->id,
                    $keys_by_model[$this->model->get_class_name()] => $this->model->id,
                ];

                $many_to_many_relation_model = new $relation[1]();
                $many_to_many_relation_model->update_attributes($update_data);

                $query = $this->db->query();
                $query->create($many_to_many_relation_model)->execute();
            }
        }

        foreach ($old_relations as $item) {
            if (!in_array($item->id, $new_relations_ids)) {
                $many_to_many_relation_model = new $relation[1]();
                $query = $this->db->query();
                $query->delete($many_to_many_relation_model)->filter_by([$keys_by_model[$item->get_class_name()], '=', $item->id], [$keys_by_model[$this->model->get_class_name()], '=', $this->model->id])->execute();
            }
        }
    }

    private function delete_one_to_one($name, $relation)
    {
        var_dump('delete_one_to_one');
    }

    private function delete_one_to_many($name, $relation)
    {
        var_dump('delete_one_to_many');
    }

    private function delete_many_to_many($name, $relation)
    {
        var_dump('delete_many_to_many');
    }

    private function get_ids($array)
    {
        $ids = [];

        if (count($array) > 0) {
            foreach ($array as $item) {
                array_push($ids, $item->id);
            }
        }

        return $ids;
    }
}

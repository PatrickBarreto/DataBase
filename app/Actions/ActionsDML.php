<?php

namespace App\Actions;

/**
 * Abstract Class with commom ressources fo the heiress class.
 */
abstract class ActionsDML{

    public string $where;
    public string $whereIn;


    public function setWhere(string $condition) {
        $this->where = $condition;
        return $this;
    }

    public function setWhereIn(string $column, array $options) {
        $options = implode(",",$options);
        $this->whereIn = "{$column} IN ({$options})}";
        return $this;
    }
}
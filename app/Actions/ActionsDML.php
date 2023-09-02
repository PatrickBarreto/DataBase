<?php

namespace App\Actions;

use App\Actions\Commands\Insert;
use App\Actions\Commands\Select;

/**
 * Abstract Class with commom ressources fo the heiress class.
 */
abstract class ActionsDML{

    public string $type = '';
    public string $table = '';
    public string $where = '';
    public string $whereIn = '';

    public function setTable(string $tableName) {
        $this->table = $tableName;
        return $this;
    }

    public function setWhere(string $condition) {
        $this->whereIn = '';
        $this->where = " WHERE {$condition}";
        return $this;
    }

    public function setWhereIn(string $column, array $options) {
        $this->where = '';
        $options = implode(",",$options);
        $this->whereIn = "WHERE {$column} IN ({$options})";
        return $this;
    }

}
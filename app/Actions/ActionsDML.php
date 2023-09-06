<?php

namespace App\Actions;

use App\Actions\QueryInterface;
use App\Exceptions\ExceptionHandler;
use App\DataBaseHandler;
use PDOStatement;

/**
 * Abstract Class with commom ressources fo the heiress class.
 */
abstract class ActionsDML extends DataBaseHandler implements QueryInterface{

    use QueryTrait;

    public string $type = '';
    public string $where = '';
    public string $whereIn = '';
    public string $query = '';
    public PDOStatement $statement;
    protected string $table = '';

    public function setTable(string $tableName) {
        $this->table = ' '.$tableName.' ';
        return $this;
    }

    public function setWhere(string $condition) {
        $this->whereIn = '';
        $this->where = "WHERE {$condition}";
        return $this;
    }

    public function setWhereIn(string $column, array $options) {
        $this->where = '';
        $options = implode(",",$options);
        $this->whereIn = "WHERE {$column} IN ({$options})";
        return $this;
    }

    public function getTableName(){
        if(empty($this->table)) {
            new ExceptionHandler("The table is not configured", 400, $this);
        }
        return $this->table;
    }

    public function runQuery(){
        return $this->tryQuery($this->query);
    }

}
<?php

namespace App\Actions;

use App\Actions\Commands\Select;
use App\Actions\QueryInterface;
use App\Exceptions\ExceptionHandler;
use App\DataBaseHandler;
use PDOStatement;
use stdClass;

/**
 * Abstract Class with commom ressources fo the heiress class.
 */
abstract class ActionsDML extends DataBaseHandler implements QueryInterface{

    use QueryTrait;

    public string $type = '';
    public string $where = '';
    public string $whereIn = '';
    public string $query = '';
    protected string $table = '';
    protected PDOStatement $statement;


    public function getTableName(){
        if(empty($this->table)) {
            new ExceptionHandler("The table is not configured", 400, $this);
        }
        return $this->table;
    }

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
        $options = $this->convertArrayToQueryPattern($options, 'whereIn');
        $this->where = '';
        $this->whereIn = "WHERE {$column} IN ({$options})";
        return $this;
    }

    /**
     * this method is responsable to run a query without a fetch result. 
     *
     * @return $this
     */
    public function runQuery(){
        $this->makeConnection();
        $this->createQueryIfNecessary();
        $this->statement = $this->tryQuery($this->query);
        return $this;
    }

    /**
     * This method id responsable to return data with an associative array
     *
     * @param bool $returnAll
     * @return array
     */
    public function fetchAssoc(bool $returnAll = false){
        $this->runQuery();
        if($returnAll){
            return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * This method is responsable to return data with an object of a class type. By default it is stdClass
     *
     * @param bool $returnAll
     * @param string $class
     * @return array|object
     */
    public function fetchObject(bool $returnAll = false, string $class = stdClass::class){
        $this->runQuery();
        if($returnAll){
            return $this->statement->fetchAll(\PDO::FETCH_CLASS, $class);
        }
        return $this->statement->fetchObject($class);
    }

    /**
     * this method is responsable to check and create a query if necessary.
     *
     * @return void
     */
    private function createQueryIfNecessary() {
        if(empty($this->query)){
            $this->buildQuery();
        }
    }



}
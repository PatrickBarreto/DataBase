<?php

namespace DataBase\Actions\DML;

use stdClass;
use PDOStatement;
use DataBase\Actions\DataBase;
use DataBase\Actions\DML\DMLInterface;
use DataBase\Actions\DML\DMLTrait;
use DataBase\Exceptions\ExceptionHandler;

/**
 * Abstract Class with commom ressources for the heiress class.
 */
abstract class DML extends DataBase implements DMLInterface{

    use DMLTrait;

    public string $type = '';
    public string $where = '';
    public string $whereIn = '';
    public string $query = '';
    public string $join = '';
    protected string $table = '';
    
    protected PDOStatement $statement;

    /**
     * This method is responsable to check if a table name was seted in the instance of class that heirded this class
     *
     * @return string
     */
    public function getTableName(){
        if(empty($this->table)) {
            new ExceptionHandler("The table is not configured", 400, $this);
        }
        return $this->table;
    }

    /**
     * This method is responsable to set a table in the instance of class that heirded this class
     *
     * @param string $tableName
     * @return $this
     */
    public function setTable(string $tableName) {
        $this->table = ' '.$tableName.' ';
        return $this;
    }

    /**
     * This method is responsable to set the Where Simple Conditction for the query of instance class that heirded this class.
     *
     * @param string $condition
     * @return $this
     */
    public function setWhere(string $condition) {
        $this->whereIn = '';
        $this->where = "WHERE {$condition}";
        return $this;
    }

    /**
     * This method is responsable to set the Where In Conditction for the query of instance class that heirded this class.
     *
     * @param string $column
     * @param array $options
     * @return $this;
     */
    public function setWhereIn(string $column, array $options) {
        $options = $this->convertArrayToQueryPattern($options, 'whereIn');
        $this->where = '';
        $this->whereIn = "WHERE {$column} IN ({$options})";
        return $this;
    }


     /**
     * This methos is responsable to do JOINS for query heir class instance.
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param string $type, Acepted Values: [ INNER, LEFT, RIGHT, FULL, CROSS ]
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return $this
     */
    public function setJoin(array $newTableJoin, string $type = 'INNER', array $joinedTable = null){    
        if($type == 'CROSS') {
            $this->join .= " {$type} JOIN {$newTableJoin['nameTable']} ";
            return $this;
        }

        if(!$joinedTable) {
           new ExceptionHandler("To do a join you need to inform the past joined table that will associate to the new join table", 400);
        }
        
        $newTableJoin['columnJoin'] = empty($newTableJoin['columnJoin']) ?  'id' : $newTableJoin['columnJoin'];
        $joinedTable['columnJoin'] = empty($joinedTable['columnJoin']) ?  'id' : $joinedTable['columnJoin'];

        $this->join .= " {$type} JOIN {$newTableJoin['nameTable']} ON {$newTableJoin['nameTable']}.{$newTableJoin['columnJoin']} = {$joinedTable['nameTable']}.{$joinedTable['columnJoin']} ";

        return $this;
    }

    /**
     * this method is responsable to run the query of the class without a fetch result. 
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
     * this method is responsable to check existense and create a query if necessary.
     *
     * @return void
     */
    private function createQueryIfNecessary() {
        if(empty($this->query)){
            $this->buildQuery();
        }
    }
}
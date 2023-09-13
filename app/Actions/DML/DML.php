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
     * This method is responsable to concat the Inner Join command
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return $this
     */
    public function setInnerJoin(array $newTableJoin, array $joinedTable) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('INNER', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Right Join command
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return $this
     */
    public function setRightJoin(array $newTableJoin, array $joinedTable) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('RIGHT', $newTableJoin, $joinedTable);
        return $this;
    }
    /**
     * This method is responsable to concat the Left Join command
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return $this
     */
    public function setLeftJoin(array $newTableJoin, array $joinedTable) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('LEFT', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Left Join command
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return $this
     */
    public function setFullJoin(array $newTableJoin, array $joinedTable) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('FULL', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Left Join command
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return $this
     */
    public function setCrossJoin(array $newTableJoin, array $joinedTable) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('CROSS', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to set id was default join columns if it wasn't declared
     *
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return void
     */
    private function setDefaultJoinColumn(array &$newTableJoin, array &$joinedTable){
        $newTableJoin['columnJoin'] = empty($newTableJoin['columnJoin']) ?  'id' : $newTableJoin['columnJoin'];
        $joinedTable['columnJoin'] = empty($joinedTable['columnJoin']) ?  'id' : $joinedTable['columnJoin'];
    }

    /**
     * This method is responsable to concat the join property
     * @param string $typeJoin
     * @param array $newTableJoin["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @param array $joinedTable["nameTable"=>"value", "columnJoin"=>"value"] *by default the columnJoin is 'id'
     * @return void
     */
    private function concatJoinProperty(string $typeJoin, array $newTableJoin, array $joinedTable = null) {
        if($typeJoin == 'CROSS' || $typeJoin == 'FULL') {
            $this->join .= " {$typeJoin} JOIN {$newTableJoin['nameTable']} ";
            return $this;
        }

        $this->join .= " {$typeJoin} JOIN {$newTableJoin['nameTable']} ON {$newTableJoin['nameTable']}.{$newTableJoin['columnJoin']} = {$joinedTable['nameTable']}.{$joinedTable['columnJoin']} ";
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
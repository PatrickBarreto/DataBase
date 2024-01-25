<?php

namespace DataBase\Actions\DML;

use stdClass;
use PDOStatement;
use DataBase\Actions\DataBase;
use DataBase\Actions\DBTrait;

/**
 * Abstract Class with commom ressources for the heiress class.
 */
abstract class DML extends DataBase {

    use DBTrait;

    public string $type = '';
    public string $where = '';
    public string $whereIn = '';
    public string $query = '';
    public string $join = '';
    
    protected PDOStatement $statement;
    
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
     * @param array $joinedTable["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @param array $newTableJoin["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @return $this
     */
    public function setInnerJoin(array $joinedTable, array $newTableJoin) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('INNER', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Right Join command
     *
     * @param array $joinedTable["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @param array $newTableJoin["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @return $this
     */
    public function setRightJoin(array $joinedTable, array $newTableJoin) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('RIGHT', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Left Join command
     *
     * @param array $joinedTable["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @param array $newTableJoin["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @return $this
     */
    public function setLeftJoin(array $joinedTable, array $newTableJoin) {
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('LEFT', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Full Join command
     *
     * @param array $newTableJoin["table"=>"value"]
     * @return $this
     */
    public function setFullJoin(array $newTableJoin) {
        $joinedTable = [];
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('FULL', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to concat the Cross Join command
     *
     * @param array $newTableJoin["table"=>"value"]
     * @return $this
     */
    public function setCrossJoin(array $newTableJoin) {
        $joinedTable = [];
        $this->setDefaultJoinColumn($newTableJoin, $joinedTable);
        $this->concatJoinProperty('CROSS', $newTableJoin, $joinedTable);
        return $this;
    }

    /**
     * This method is responsable to set id was default join columns if it wasn't declared
     *
     * @param array $newTableJoin["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @param array $joinedTable["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @return void
     */
    private function setDefaultJoinColumn(array &$newTableJoin, array &$joinedTable){
        $newTableJoin['ON'] = empty($newTableJoin['ON']) ?  'id' : $newTableJoin['ON'];
        
        if (empty($joinedTable)) return;

        $joinedTable['ON'] = empty($joinedTable['ON']) ?  'id' : $joinedTable['ON'];
    }

    /**
     * This method is responsable to concat the join property
     * @param string $typeJoin
     * @param array $newTableJoin["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @param array $joinedTable["table"=>"value", "ON"=>"value"] *by default ON is 'table.id'
     * @return void
     */
    private function concatJoinProperty(string $typeJoin, array $newTableJoin, array $joinedTable = null) {
        if($typeJoin == 'CROSS' || $typeJoin == 'FULL') {
            $this->join .= " {$typeJoin} JOIN {$newTableJoin['table']} ";
            return $this;
        }

        $this->join .= " {$typeJoin} JOIN {$newTableJoin['table']} ON {$newTableJoin['table']}.{$newTableJoin['ON']} = {$joinedTable['table']}.{$joinedTable['ON']} ";
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
}
<?php

namespace DataBase\Actions;

use Exception\Exception;

/**
 * Trait with functions that can be useful for more than one class, to solte pontual problems, 
 * but not necessarilly are direct part of the conection and manipulate database flow.
 */
trait DBTrait{


    /**
     * This method is responsable to check if a table name was seted in the instance of class that heirded this class
     *
     * @return string
     */
    public function getTableName(){
        if(empty($this->table)) {
            Exception::throw("The table is not configured", 400);
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
     * Prepare data by the format that a query need
     *
     * @param string $data
     * @return void
     */
    protected function prepareDataForQuery(string $data) {
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($data)) return 0;
        if(is_numeric($data)) return $data;
        if(is_string($data)) return "'".$data."'";
    }

    /**
     * Prepare array data for the type of query.
     *
     * @param array $values
     * @param string $type
     * @return void
     */
    protected function convertArrayToQueryPattern(array $values, string $type) {
        
        foreach($values as $key => $item){ 
            if(is_array($item)){
                 foreach($item as $key2 => $subItem){
                      $formatData[$key][$key2] = $this->prepareDataForQuery($subItem);
                }
                if($type == 'insert') $formatValues[] = '('.implode(', ', $formatData[$key]).')';
                if($type == 'update') $formatValues[] = $key2.' = '.implode(', ', $formatData[$key]);
               continue;
           } 

           $formatData[$key] = $this->prepareDataForQuery($item);
           if($key == array_key_last($values)){
                if($type == 'insert') $formatValues[] = '('.implode(', ', $formatData).')';
                if($type == 'update') $formatValues[] =  $key.' = '.implode(', ', $formatData);
                if($type == 'whereIn') $formatValues[] = implode(', ', $formatData);
                if($type == 'join') $formatValues[] = implode(' ', $formatData);
           }
       }

       return implode(', ', $formatValues);
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
     * this method is responsable to check existense and create a query if necessary.
     *
     * @return void
     */
    private function createQueryIfNecessary() {
        if(empty($this->query)){
            $this->buildQuery();
        }
    }



    private function setIntTypeForVetor(array $values) {
        foreach($values as $key1=>$value) {
            foreach($value as $key2=>$item) {
                if(isset($item) && preg_match('/^[0-9]*$/', $item)){
                    $values[$key1][$key2] = (int)$item;
                }
            }
        }
        return $values;
    }

    private function setIntTypeForArray(array $value) {
        foreach($value as $key1=>$item) {
            if(isset($item) && preg_match('/^[0-9]*$/', $item)){
                $value[$key1] = (int)$item;
            }
        }
        return $value;
    }


    private function setIntTypeForObjectVetor(array $values) {
        foreach($values as $key1=>$value) {
            foreach($value as $key2=>$item) {
                if(isset($item) && preg_match('/^[0-9]*$/', $item)){
                    $values[$key1]->$key2 = (int)$item;
                }
            }
        }
        return $values;
    }


    private function setIntTypeForObject(object $value) {
        foreach($value as $key=>$item) {
            if(isset($item) && preg_match('/^[0-9]*$/', $item)){
                $value->$key = (int)$item;
            }
        }
        return $value;
    }


}
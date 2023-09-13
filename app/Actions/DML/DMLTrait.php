<?php

namespace DataBase\Actions\DML;

/**
 * Trait with functions that can be useful for more than one class, to solte pontual problems, 
 * but not necessarilly are direct part of the conection and manipulate database flow.
 */
trait DMLTrait{
    
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

}
<?php

namespace DataBase\Actions\DML\Commands;

use DataBase\Actions\DML\DML;

/**
 * Class responsable to be a Insert query constructor
 */
class Insert extends DML {
     
     public bool $ignore = false;
     public string $fields;
     public string $values; 
     public string $insertSelect; 


     /**
     * Set the ignore option, by default it is false
     *
     * @param boolean $requiereIgnore
     * @return Insert
     */
     public function setIgnore(bool $requiereIgnore) {
          $this->ignore = $requiereIgnore;
          return $this;
     }

     /**
     * Set the fields option, by default it is false
     *
     * @param array $fields
     * @return Insert
     */
     public function setFields(array $fields) {
          $this->fields = implode(",",$fields);
          return $this;
     }

     /**
     * Set the values option, by default it is false
     *
     * @param array $values
     * @return Insert
     */
     public function setValues(array $values) {
          $this->insertSelect = '';
          $this->values = "VALUES {$this->convertArrayToQueryPattern($values, 'insert')}";
          return $this;
     }

     /**
     * Set the values with a SELECT query
     *
     * @param string $query
     * @return Insert
     */
     public function setInsertSelect(string $query) {
          $this->values = '';
          $this->insertSelect = $query;
          return $this;
     }


     /**
      * Build the query sentense for a Insert query
      *
      * @return Insert
      */
     public function buildQuery() {
          $ignore = $this->ignore ? " IGNORE " : null;
          $table = $this->getTableName();
          $query = "INSERT {$ignore} INTO {$table}({$this->fields}) {$this->values} {$this->insertSelect}";
          $this->query = $query;
          return $this;
     }


}
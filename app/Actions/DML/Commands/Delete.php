<?php

namespace DataBase\Actions\DML\Commands;

use DataBase\Actions\DML\DML;
use DataBase\Exceptions\ExceptionHandler;

/**
 * Class responsable to be a Delete query constructor
 */
class Delete extends DML{

    /**
     * Build the query sentense for a delete query
     *
     * @return Delete
     */
     public function buildQuery(){
          $table = $this->getTableName();
          if($this->where || $this->whereIn){
               $tableToDelete = empty($this->join) ? '' : $table;
               $query = "DELETE {$tableToDelete} FROM {$table} {$this->join} {$this->where} {$this->whereIn}";
               $this->query = $query;
               return $this;
          }
          new ExceptionHandler("Anyone Where Condiction needs to be seted to run a Delete command", 400, $this);
     }
}
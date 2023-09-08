<?php

namespace App\Actions\Commands;

use App\Actions\ActionsDML;
use App\Exceptions\ExceptionHandler;

/**
 * Class responsable to be a Delete query constructor
 */
class Delete extends ActionsDML{

    /**
     * Build the query sentense for a delete query
     *
     * @return Delete
     */
     public function buildQuery(){
          $table = $this->getTableName();
          if($this->where || $this->whereIn){
               $query = "DELETE FROM {$table} {$this->where} {$this->whereIn}";
               $this->query = $query;
               return $this;
          }
          new ExceptionHandler("Anyone Where Condiction needs to be seted to run a Delete command", 400, $this);
     }
}
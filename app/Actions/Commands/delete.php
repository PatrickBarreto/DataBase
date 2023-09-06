<?php

namespace App\Actions\Commands;

use App\Actions\ActionsDML;
use App\Exceptions\ExceptionHandler;

class Delete extends ActionsDML{

     public function buildQuery(){
          $table = $this->getTableName();
          if($this->where || $this->whereIn){
               $query = "DELETE FROM {$table} {$this->where} {$this->whereIn}";
               $this->query = $query;
               return $query;
          }
          new ExceptionHandler("Any Where Condiction needs to be seted to run a Delete command", 400, $this);
     }
}
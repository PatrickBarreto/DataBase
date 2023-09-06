<?php 

namespace App\Actions\Commands;

use App\Actions\ActionsDML;

class Update extends ActionsDML{
     
     public string $set = '';

     public function setSet(array $columnValue) {
          $this->set = "SET {$this->convertArrayToQueryPattern($columnValue, 'update')}";
          return $this;
     }

     public function buildQuery(){
          $table = $this->getTableName();
          $query = "UPDATE {$table} {$this->set} {$this->where} {$this->whereIn}";
          $this->query = $query;
          return $query;
     }
}
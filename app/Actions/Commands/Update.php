<?php 

namespace App\Actions\Commands;

use App\Actions\ActionsDML;

/**
 * Class responsable to be a Update query constructor
 */
class Update extends ActionsDML{
     
     public string $set = '';

     /**
      * This method is responsable for set a SET command for an Update query
      *
      * @param array $columnValue
      * @return Update
      */
     public function setSet(array $columnValue) {
          $this->set = "SET {$this->convertArrayToQueryPattern($columnValue, 'update')}";
          return $this;
     }

     /**
      * Build the query sentense for a Update query
      *
      * @return Update
      */
     public function buildQuery(){
          $table = $this->getTableName();
          $query = "UPDATE {$table} {$this->set} {$this->where} {$this->whereIn}";
          $this->query = $query;
          return $query;
     }
}

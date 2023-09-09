<?php

namespace DataBase;

use DataBase\Actions\DML\Commands\Insert;
use DataBase\Actions\DML\Commands\Select;
use DataBase\Actions\DML\Commands\Update;
use DataBase\Actions\DML\Commands\Delete;

/**
 * This class is responsable to do a unique instance to run the CRUD commands with and common table, 
 * to don't need to set the table in all the classes instance.
 */
class Crud {

    public string $table;
    
    public Insert $insert;
    
    public Select $select;
    
    public Update $update;
    
    public Delete $delete;
    
    public function __construct(string $table) {
        $this->table = $table;
        $this->insert = (new Insert)->setTable($table);
        $this->select = (new Select)->setTable($table);
        $this->update = (new Update)->setTable($table);
        $this->delete = (new Delete)->setTable($table);
        return $this;
    }
}

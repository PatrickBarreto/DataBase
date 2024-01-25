<?php

namespace DataBase;

use DataBase\Actions\DML\Commands\Insert;
use DataBase\Actions\DML\Commands\Select;
use DataBase\Actions\DML\Commands\Update;
use DataBase\Actions\DML\Commands\Delete;

/**
 * This class is extensible to give to your model CRUD actions
 */
abstract class CrudExtension {

    public static string $table;
    
    public Insert $insert;
    
    public Select $select;
    
    public Update $update;
    
    public Delete $delete;
    
    public function __construct() {
        self::$table = static::$table;
        $this->insert = (new Insert)->setTable(self::$table);
        $this->select = (new Select)->setTable(self::$table);
        $this->update = (new Update)->setTable(self::$table);
        $this->delete = (new Delete)->setTable(self::$table);
        return $this;
    }
}

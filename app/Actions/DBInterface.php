<?php

namespace DataBase\Actions;

/**
 * Interface of the DML commands, comands that will manipulate data on the data base. 
 * Here have the absolute necessarylly and essential methods for the DML commands
 */
interface DBInterface {
    public function buildQuery();
    public function setTable(string $tableName);
}
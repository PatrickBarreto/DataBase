<?php

namespace DataBase;

use DataBase\Actions\DataBase;

class Query extends DataBase{

    /**
     * This method is responsable to prepare to execute directyly anyone user query
     *
     * @param string $query
     * @return PDOStatement
     */
    public function runQuery(string $query){
        $this->makeConnection();
        return $this->tryQuery($query);
    }

    /**
     * This function is to be called without the dev make an instance of this class.
     *
     * @param string $query
     * @return PDOStatement
     */
    public static function exec(string $query){
        $selfClass = new self;
        return $selfClass->runQuery($query);
    }
}
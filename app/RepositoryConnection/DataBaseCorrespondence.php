<?php

namespace DataBase\RepositoryConnection;

use Exception\Exception;
use DataBase\RepositoryConnection\DataBaseCorrespondenceInterface;

abstract class DataBaseCorrespondence implements DataBaseCorrespondenceInterface {

    public function getTableName(){
        if(property_exists(static::class, 'table')){
            $table = static::getTable();
            return $table;
        }
        Exception::throw("the private table property doesn't exists in: ".static::class, 500);
    }

    public function getDto(){
        return static::class;
    }

}
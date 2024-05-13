<?php

namespace DataBase\RepositoryConnection;

use DataBase\Actions\DML\Commands\Insert;
use DataBase\Actions\DML\Commands\Select;
use DataBase\Actions\DML\Commands\Update;
use DataBase\Actions\DML\Commands\Delete;
use DataBase\RepositoryConnection\DataBaseCorrespondence;

/**
 * This class is extensible to give to your model CRUD actions
 */
class Repository {

    private string $table;
    
    private DataBaseCorrespondence $dto;

    private array $action = [];
    
    public function __construct(DataBaseCorrespondence $dto){
        $this->dto = $dto;
        $this->setTable($dto->getTableName());
        $this->action = [
            "insert" => function(){ return (new Insert)->setTable($this->table); },
            "select" => function(){ return (new Select)->setTable($this->table); },
            "update" => function(){ return (new Update)->setTable($this->table); },
            "delete" => function(){ return (new Delete)->setTable($this->table); }
        ];
        return $this;
    }
    
    public function table(){
        return $this->table;
    }

    public function getDtoPath(){
        return $this->dto->getDto();
    }
    
    public function setTable(string $tableName){
        $this->table = $tableName;
    }

    public function select(){
        return $this->action['select']();
    }

    public function insert(){
        return $this->action['insert']();
    }

    public function update(){
        return $this->action['update']();
    }
    
    public function delete(){
        return $this->action['delete']();
    }
}

<?php

namespace DataBase\Actions;

use PDO;
use PDOException;
use PDOStatement;
use DataBase\Actions\DBInterface;
use Exception\Exception;

/**
 * Abstract class with ressources that anyone data base action will need, this class care data base layer methods.
 */
abstract class DataBase implements DBInterface {

    private PDO $pdo;
    protected string $table = '';

    /**
     * This method is responsible for building the PDO instance for manipulation with the database.
     *
     */
    protected function makeConnection(){        
        try{
            $this->pdo = new PDO(getenv('DB_CONNECTION').':host='.getenv('DB_HOST').':'.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),getenv('DB_PASSWORD'),$this->setOptionsPDO());
        }catch(PDOException $e){
           echo $e->getMessage();
           exit;
        }
    }

    /**
     * Method responsible for trying to insert a single unit of data, a record line. The method triggers the sql exception if there is an error.
     *
     * @param string $query
     * @return PDOStatement
     */
    protected function tryQuery(string $query){
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->query($query);
            $lastInsertId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return ["statement"=>$statement, "lastInsertId"=>$lastInsertId];

        }catch(PDOException $e){
            $this->pdo->rollBack();
            if(getenv('DB_DEBUG_BAD_QUERY') === "true"){
                Exception::throw($e->getMessage(), 400, ['query' => $query]);
            }
            Exception::throw($e->getMessage(), 400,);
        }
    }

    /**
     * This method configures the PDO connection to generate fault exceptions according to the value passed in the DB_DEBUG environment variable.
     *
     * @return array
     */
    private function setOptionsPDO(){
        if(getenv('DB_DEBUG')) {
            return [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        }
        return [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT];
    }


}
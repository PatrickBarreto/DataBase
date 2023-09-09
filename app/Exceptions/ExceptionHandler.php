<?php

namespace DataBase\Exceptions;

use Exception;

class ExceptionHandler {

    public function __construct(string $message, int $status, object $specialItensException = null){
        try{
            throw new Exception($message, $status);
        }catch(Exception $e){
            header('content-type:application-json');
            header("status:{$e->getCode()}");
            exit(json_encode(["erro" => $e->getMessage(),"status"=> $e->getCode(), "aditional_informations" => $specialItensException], JSON_PRETTY_PRINT));
        }
    }
}


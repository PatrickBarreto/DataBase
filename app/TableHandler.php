<?php

namespace App\Handlers\DataBaseHandler;

use App\Handlers\DataBaseHandler\HandlerRessources\ActionDataBase;
use App\Handlers\ExceptionHandler;

class TableHandler extends DataBaseHandler{
      
    
    /**
     * Método responsável por deletar informações na tabela setada na instância desta classe.
     *
     * @param ActionDataBase $action
     * @return boolean
     */
    public function delete(ActionDataBase $action) {
        if(!isset($action->optionsAction->where) || !$action->optionsAction->where){
            new ExceptionHandler("Por segurança não é permitido DELETE sem WHERE", 400);
        }
        $query = $this->queryBuilder($action);
        $this->tryQuery($query);
        return true;
    }
}
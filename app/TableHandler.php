<?php

namespace App\Handlers\DataBaseHandler;

use App\Handlers\DataBaseHandler\HandlerRessources\ActionDataBase;
use App\Handlers\ExceptionHandler;

class TableHandler extends DataBaseHandler{
      
    /**
     * Método responsável por inserir dados na tabela que a instância desta classe possuir.
     * 
     * @param ActionDataBase $action
     * @return boolean
     */
    public function insert(ActionDataBase $action) {
    
        $dataInsert = (array)$action->optionsAction->values;
        $action->optionsAction->values = [];
        foreach($dataInsert as $key => $item){
            if(is_array($item)){
                foreach($item as $key2 => $subItem){
                    $formatData[$key][$key2] = $this->prepareDataForQuery($subItem);
                }
                $action->optionsAction->values[] = '('.implode(', ', $formatData[$key]).')';
                continue;
            } 
            $formatData[$key] = $this->prepareDataForQuery($item);
            if($key == array_key_last($dataInsert)){
                $action->optionsAction->values[] = '('.implode(', ', $formatData).')';
            }
        }
        $action->optionsAction->values = implode(', ', $action->optionsAction->values);

        $query = $this->queryBuilder($action);

        $this->tryQuery($query);
        
        return true;
    }


    /**
     * Método responsável por consultar dados na tabela setada na instância desta classe.
     *
     * @param ActionDataBase $action
     * @return array
     */
    public function select(ActionDataBase $action, $fetched = true) {
        $query = $this->queryBuilder($action);
        $return = $this->tryQuery($query)->fetchAll(\PDO::FETCH_ASSOC);
        
        if(!$fetched){
            $return = $this->tryQuery($query);
        }
        
        return $return;
    }


    /**
     * Método responsável por editar informações na tabela setada na instância desta classe.
     *
     * @param ActionDataBase $action
     * @return boolean
     */
    public function update(ActionDataBase $action) {    
        $dataUpdate = (array)$action->optionsAction->set;
        $action->optionsAction->set = [];
        foreach($dataUpdate as $key => $item){
            if(is_array($item)){
                foreach($item as $key2 => $subItem){
                    $formatData[$key][$key2] = $this->prepareDataForQuery($subItem);
                }
                $action->optionsAction->set[] = $key2.' = '.implode(', ', $formatData[$key]);
                continue;
            } 
            $formatData[$key] = $this->prepareDataForQuery($item);
            if($key == array_key_last($dataUpdate)){
                $action->optionsAction->set[] = $key.' = '.implode(', ', $formatData);
            }
        }
        $action->optionsAction->set = implode(', ', $action->optionsAction->set);

        $query = $this->queryBuilder($action);

        $this->tryQuery($query);
        
        return true;
    }
    
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
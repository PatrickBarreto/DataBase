<?php

namespace App\Handlers\DataBaseHandler;

use App\Handlers\DataBaseHandler\HandlerRessources\ActionDataBase;
use PDO;
use PDOException;
use PDOStatement;
use App\Handlers\ExceptionHandler;


/**
 * Classe de recursos para manipular as operações de banco de dados de necessidade do app, como CRUD simples e abstração de PDO, bindValue e outros recursos nativos do PDO para conexão e manipulação
 * da base de dados
 */
abstract class DataBaseHandler {

    protected PDO $pdo;
    protected string $table;

    /**
     * Método responsável por construir a instância da classe, iniciando o PDO
     *
     * @param string|null $table
     */
    public function __construct(string $table = null){        
        try{
            $this->pdo = new PDO(getenv('DB_CONNECTION').':host='.getenv('DB_HOST').':'.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'),
                                    getenv('DB_USERNAME'),getenv('DB_PASSWORD'),$this->setOptionsPDO());
            $this->table = $table ?? '';
        }catch(PDOException $e){
           echo $e->getMessage();
           exit;
        }
    }

    /**
     * Método responsável por passar uma tabela para a instância da classe
     *
     * @param string $table
     * @return void
     */
    public function setTable(string $table){
        $this->table = $table;
        return $this;
    }

    /**
     * Método responsável por construir uma instrução DML de acordo com as propriedades do objeto recebido no parâmetro.
     *
     * @param ActionDataBase $action
     * @return string
     */
    protected function queryBuilder(ActionDataBase $action){
        $searchFields   = (isset($action->optionsAction->fields)    && $action->optionsAction->fields && $action->nameAction == 'select') ? $action->optionsAction->fields : "*";
        $insertFields   = (isset($action->optionsAction->fields)    && $action->optionsAction->fields && $action->nameAction == 'insert') ? '('.$action->optionsAction->fields.')' : '';
        
        $ignore         = (isset($action->optionsAction->ignore)    && $action->optionsAction->ignore == true) ? 'IGNORE' : '';
        $distinct       = (isset($action->optionsAction->distinct)  && $action->optionsAction->distinct == true) ? 'DISTINCT' : '';
        $values         = (isset($action->optionsAction->values)    && $action->optionsAction->values)  ? $action->optionsAction->values : '';
        
        $where          = (isset($action->optionsAction->where)     && $action->optionsAction->where)   ? 'WHERE '.$action->optionsAction->where : '';
        $limit          = (isset($action->optionsAction->limit)     && $action->optionsAction->limit)   ? 'LIMIT '.$action->optionsAction->limit : '';
        $order          = (isset($action->optionsAction->order)     && $action->optionsAction->order)   ? 'ORDER BY '.$action->optionsAction->order : '';
        $group          = (isset($action->optionsAction->group)     && $action->optionsAction->group)   ? 'GROUP BY '.$action->optionsAction->group : '';
        $having         = (isset($action->optionsAction->having)    && $action->optionsAction->having)  ? 'HAVING '.$action->optionsAction->having : '';
        $set            = (isset($action->optionsAction->set)       && $action->optionsAction->set)     ? 'SET '.$action->optionsAction->set : '';
        
        
        $queryOptions = [
            "insert" => "INSERT {$ignore} INTO {$this->checkIfTableNameWasHaveToSet()}{$insertFields} VALUES {$values}",
            "select" => "SELECT {$distinct} {$searchFields} FROM {$this->checkIfTableNameWasHaveToSet()} {$where} {$group} {$having} {$order} {$limit}",
            "update" => "UPDATE {$this->checkIfTableNameWasHaveToSet()} {$set} {$where}",
            "delete" => "DELETE FROM {$this->checkIfTableNameWasHaveToSet()} {$where}"
        ];

        return $queryOptions[$action->nameAction];
        
    }

    /**
     * Método responsável por tentar inserir uma única unidade de dados, uma linha de registro. O método dispara excessão do sql se houver erro.
     *
     * @param string $query
     * @return PDOStatement
     */
    protected function tryQuery(string $query){
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->query($query);
            $this->pdo->commit();
            return $statement;

        }catch(PDOException $e){
            $this->pdo->rollBack();
            if(getenv('DB_DEBUG_BAD_QUERY') === "true"){
                new ExceptionHandler($e->getMessage(), 400, ['query' => $query]);
            }
            new ExceptionHandler($e->getMessage(), 400);
        }
    }

    /**
     * Método responsável por aplicar os padrões que o SQL querer para cada tipo de dado dentro da query.
     *
     * @param string $data
     * @return void
     */
    protected function prepareDataForQuery(string $data) {
        
        $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if(empty($data)) return 0;
        if(is_numeric($data)) return $data;
        if(is_string($data)) return "'".$data."'";
    }

    /**
     * Método que verifica no ato de uma operação SQL se uma tabela foi indicada para a instância da classe conseguir acessar corretamente o banco.
     *
     * @return string
     */
    protected function checkIfTableNameWasHaveToSet(){
        if($this->table){
            return $this->table;
        }
        new ExceptionHandler("O nome da tabela não foi informado ao ser instanciada", 400);
    }

    /**
     * Configura a conexão PDO para gerar excessões de falhas de acordo com o valor passado na variável de ambiente DB_DEBUG.
     *
     * @return array
     */
    private function setOptionsPDO(){
        if(env('DB_DEBUG')) {
            return [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        }
        return [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT];
    }


}
<?php

namespace App;

use PDO;
use PDOException;
use PDOStatement;
use App\Exceptions\ExceptionHandler;


/**
 * Classe de recursos para manipular as operações de banco de dados de necessidade do app, como CRUD simples e abstração de PDO, bindValue e outros recursos nativos do PDO para conexão e manipulação
 * da base de dados
 */
abstract class DataBaseHandler {

    protected PDO $pdo;

    /**
     * Método responsável por construir a instância da classe, iniciando o PDO
     *
     */
    public function __construct(){        
        try{
            $this->pdo = new PDO(getenv('DB_CONNECTION').':host='.getenv('DB_HOST').':'.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),getenv('DB_PASSWORD'),$this->setOptionsPDO());
        }catch(PDOException $e){
           echo $e->getMessage();
           exit;
        }
    }

    /**
     * Método responsável por tentar inserir uma única unidade de dados, uma linha de registro. O método dispara excessão do sql se houver erro.
     *
     * @param string $query
     * @return array
     */
    protected function tryQuery(string $query){
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->query($query);
            $this->pdo->commit();
            return ['statement'=>$statement, 'object' => $this];

        }catch(PDOException $e){
            $this->pdo->rollBack();
            if(getenv('DB_DEBUG_BAD_QUERY') === "true"){
                new ExceptionHandler($e->getMessage(), 400, (object)['query' => $query]);
            }
            new ExceptionHandler($e->getMessage(), 400);
        }
    }
   
    /**
     * Configura a conexão PDO para gerar excessões de falhas de acordo com o valor passado na variável de ambiente DB_DEBUG.
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
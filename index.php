<?php

require "vendor/autoload.php";

use App\Actions\Commands\Select;
use App\Actions\Commands\Insert;
use App\Actions\Commands\Update;
use App\Actions\Commands\Delete;

use App\Handlers\DataBaseHandler\TableHandler;

$select = (new Select)
        ->setTable('teste_tabela')
        ->setDistinct(true)
        ->setFields(['teste, teste2'])
        ->setWhere('teste = 1')
        ->buildQuery(true);


        
$insert = (new Insert)
        ->setTable('teste_tabela')
        ->setFields(['teste', 'teste2'])
        ->setValues(['teste',2,3])
        ->buildQuery();



$update = (new Update)
        ->setTable('teste_tabela')
        ->setSet([['teste' => 3], ['teste' => 'oi']])
        ->setWhere($select)
        ->buildQuery();

$delete = (new Delete)
        ->setTable('teste_tabela')
        ->setWhere('teste=1')
        ->buildQuery();




// INICIO DEBUG
    echo ((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) / 1000).' ms';
    echo '<pre>'; var_dump('',$select, $insert, $update, $delete); echo '</pre>'; die;
// FIM DEBUG
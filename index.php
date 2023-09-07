<?php

require "vendor/autoload.php";

use App\Actions\Commands\Select;
use App\Actions\Commands\Insert;
use App\Actions\Commands\Update;
use App\Actions\Commands\Delete;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$delete = (new Delete)
        ->setTable('teste')
        ->setWhereIn('nome',['pedro','maria','josé'])
        ->buildQuery()
        ->runQuery();


$insert = (new Insert)
        ->setTable('teste')
        ->setFields(['nome'])
        ->setValues([['pedro'], ['maria'], ['josé']])
        ->buildQuery()
        ->runQuery();
        
$select = (new Select)
        ->setTable('teste')
        ->setDistinct(false)
        ->setFields(['nome'])
        ->fetchAssoc(true);

// INICIO DEBUG
    echo ((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) / 1000).' ms';
    echo '<pre>'; var_dump($select); echo '</pre>'; die;
// FIM DEBUG

// $update = (new Update)
//         ->setTable('teste')
//         ->setSet(['nome' => 'joão'])
//         ->setWhere("nome = 'pedro'");

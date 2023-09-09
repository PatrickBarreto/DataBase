<?php

require "vendor/autoload.php";

use DataBase\Crud;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();


$crudTeste = new Crud('teste');
// INICIO DEBUG
        echo ((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) / 1000).' ms';
        echo '<pre>'; var_dump($crudTeste->select->setWhere('nome = "pedro"')->fetchObject(true)); echo '</pre>';
        echo '<pre>'; var_dump($crudTeste->insert
                                                ->setFields(['nome'])
                                                ->setValues(["pedro"])
                                                ->runQuery()); echo '</pre>'; die;
// FIM DEBUG
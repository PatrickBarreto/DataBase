<?php

require "vendor/autoload.php";

use App\Actions\Commands\Select;
use App\Actions\Commands\Insert;
use App\Actions\Commands\Update;
use App\Actions\Commands\Delete;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$select = (new Select)
        ->setTable('teeste')
        ->setDistinct(false)
        ->setFields(['nome'])
        ->setWhere('nome = "Pedro"')
        ->buildQuery(true)
        ->runQuery();
        
$insert = (new Insert)
        ->setTable('teste')
        ->setFields(['nome'])
        ->setInsertSelect($select['object']->query)
        ->buildQuery()
        ->runQuery();

$update = (new Update)
        ->setTable('teste')
        ->setSet(['nome' => 'joão'])
        ->setWhere($select['object']->query)
        ->buildQuery();


$delete = (new Delete)
        ->setTable('teste_tabela')
        ->setWhere('teste=1')
        ->buildQuery();

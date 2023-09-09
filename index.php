<?php

require "vendor/autoload.php";

use App\Actions\Commands\Select;
use App\Actions\Commands\Insert;
use App\Actions\Commands\Update;
use App\Actions\Commands\Delete;
use App\Actions\Commands\Query;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$select = (new Select)
        ->setTable('teste')
        ->setDistinct(false)
        ->setOrder('nome', 'ASC')
        ->buildQuery()
        ->query;

$statement = Query::exec($select)->fetchAll(PDO::FETCH_ASSOC);

// INICIO DEBUG
        echo ((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) / 1000).' ms';
        echo '<pre>'; var_dump($statement); echo '</pre>'; die;
// FIM DEBUG
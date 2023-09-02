<?php

require "vendor/autoload.php";

use App\Actions\Commands\Select;
use App\Actions\Commands\Insert;

$select = (new Select)
            ->setFields(['teste','teste'])
            ->setOrder("teste", "ASC")
            ->setWhereIn("teste", [1,2,3]);

$insert = (new Insert)->setFields(['teste', 'teste']);

// INICIO DEBUG
    echo ((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) / 1000).' ms';
    echo '<pre>'; var_dump($select, $insert); echo '</pre>'; die;
// FIM DEBU
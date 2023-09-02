<?php

require "vendor/autoload.php";

use App\Actions\Commands\Select;
use App\Actions\Commands\Insert;

$insert = (new Insert)
            ->setFields(['teste', 'teste'])
            ->setValues([['coluna'=>'valor'],
                         ['coluna'=>'valor']]
                        );

// INICIO DEBUG
    echo ((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) / 1000).' ms';
    echo '<pre>'; var_dump($insert); echo '</pre>'; die;
// FIM DEBU
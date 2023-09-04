<?php

namespace App\Actions;

interface QueryInterface {
    public function buildQuery();
    public function setTable(string $tableName);
}
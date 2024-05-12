<?php

namespace DataBase\RepositoryConnection;

interface DataBaseCorrespondenceInterface {
    public static function getTable();
    public function getProperty(string $propertyName);
}
<?php

namespace Model;
use PDO;

abstract class Model
{
    protected \PDO $pdo;

    public function getPDO():PDO
    {
        $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'user', 'pass');
        return $pdo;
    }

    abstract protected function getTableName():string;
}
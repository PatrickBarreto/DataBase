# About Project
If you dont't have a php environment with a database, you can use this docker environment: 
https://github.com/PatrickBarreto/baseBackend

The next step of this code is:
- Implement the essentials DDL Commands (CREATE, DROP, TRUNCATE, ALTER)

If you have an idea or pull request to make, do it. Ideas are very welcome.

# How to Install
```sh
composer require patrick-barreto/data-base
```

# .env
If in your project you don't have any .env loader lib, in this project you will downlod the vlucas/dotenv. 
To initiate the .env variables you need to run this command in your index.php or other archive that run before then Data Base Actions
- Important: 
  - The .env must be in __DIR__ value.
  - The .env.enxemple variables that the data base lib needs is in vendor/patrick-barreto/data-base/.env.exemple, copy this and put in your .env.

```sh
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();
```

# How to Use
### Use the Crud Instance
```php
<?php

namespace Your\NameSpace

use DataBase\Crud;

$homens = new Crud('homens');
$mulheres = new Crud('mulheres');
$animais = new Crud('animais');


$homens->insert->setFields(['name'])->setValues([["Pedro"],["João"],["Paulo"]])->runQuery();

$mulheres->insert->setFields(['name'])->setValues([["Maria"],["Ana"],["Marcia"]])->runQuery();

$animais->insert->setFields(['name'])->setValues([["Gato"],["Cachorro"],["Papagaio"]])->runQuery();

$mulheres->select->setFields(['homens.name as homemName, mulheres.name as mulherName'])
                ->setInnerJoin(['nameTable' => 'homens'], ['nameTable' => 'mulheres'] )
                ->setWhere('homens.name = "João"');

//LIST ALL SEARCHED VALUES
var_dump($mulheres->select->fetchObject(true)); echo '</pre>'; die;este->select->fetchObject(true);
```


### Use the Command Instance

```php
<?php

namespace Your\NameSpace

use DataBase\Actions\DML\Commands\Select;

$select = new Select;
$select->setTable('tableName');
$select->setWhereIn('nome', ['pedro', 'joão', 'josé']);
$select->fetchAssoc(true);
```

### Use the Query Class
IMPORTANT: Be carefull, here you controll what will be running. The application won't do nothing but execute the query.
```php
<?php

namespace Your\NameSpace

use DataBase\Query;

$sql = "SELECT * FROM teste";
$query = new Query;
$result = $query->runQuery($sql)->fetchAll(PDO::FETCH_ASSOC);

```



# Ressources for DML command Class
It is the same if instance manually or directilly with Crud Instance


### Common for all down classes
This method is responsable to check if a table name was seted in the instance of class that heirded this clas
  - getTableName()

This method is responsable to set a table in the instance of class that heirded this clas
  - setTable(string $tableName) 

This method is responsable to set the Where Simple Conditction for the query of instance class that heirded this class
  - setWhere(string $condition) 

This method is responsable to set the Where In Conditction for the query of instance class that heirded this class
  - setWhereIn(string $column, array $options)

This method is responsable to concat the Inner Join command
  - setInnerJoin(array $newTableJoin, array $joinedTable)

This method is responsable to concat the Right Join command
  - setRightJoin(array $newTableJoin, array $joinedTable)

This method is responsable to concat the Left Join command
  - setLeftJoin(array $newTableJoin, array $joinedTable)

This method is responsable to concat the Full Join command
  - setFullJoin(array $newTableJoin, array $joinedTable)

This method is responsable to concat the Cross Join command
  - setCrossJoin(array $newTableJoin, array $joinedTable)


## Select
### DataBase\Actions\DML\Commands\Select;
Set the distinct option, by default it is false
  - setDistinct(bool $requiereDistinct)

Set the fields option, by default its '*'
  - setFields(array $fields)

Set the limit option
  - setLimit(int $limit, int $offset = 0)

Set the order option, ASC or DESC
  - setOrder(string $fields, string $order = "ASC")

Set the group by option.
  - setGroupBy(array $fields)

Set the having option
  - setHaving(string $condition)

Build the query sentense for a Select query
  - buildQuery(bool $subquery = false)
 
This method id responsable to return data with an associative array
  - fetchAssoc(bool $returnAll = false)

This method is responsable to return data with an object of a class type. By default it is stdClass
  - fetchObject(bool $returnAll = false, string $class = stdClass::class)



## Insert
### DataBase\Actions\DML\Commands\Insert;

Set the ignore option, by default it is false
  - setIgnore(bool $requiereIgnore)

Set the fields option, by default it is false
  - setFields(array $fields)

Set the values option, by default it is false
  - setValues(array $values)

Set the values with a SELECT query
  - setInsertSelect(string $query)
 
Build the query sentense for a Insert query
  - buildQuery()

This method is responsable to run the query of the class without a fetch result.
  - runQuery()



## Update
### DataBase\Actions\DML\Commands\Update;
This method is responsable for set a SET command for an Update query
  - setSet(array $columnValue)

Build the query sentense for a Update query
  - buildQuery()
 
This method is responsable to run the query of the class without a fetch result.
  - runQuery()


## Delete
### DataBase\Actions\DML\Commands\Delete
Build the query sentense for a delete query
  - buildQuery()

This method is responsable to run the query of the class without a fetch result.
  - runQuery()

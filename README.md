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
You need to fill your environments variables. If you want make it easy use https://packagist.org/packages/patrick-barreto/dot-env

```php
DotEnv\DotEnv::fill(PATH_.ENV_FILE);
```

# How to Use

### Repository

```php
//Model
class Users extends DataBaseCorrespondence {
    private static string $table = 'users';

    public static function getTable(){
        return self::$table;
    }

    public function getProperty($property){
        return $this->$property;
    }
}


//Repository
class UserRepository extends Repository{

    public function findUsers() {
        return $this->select()->setFields(['id', 'name', 'email', 'phone'])
                              ->fetchObject(false, $this->getDtoPath());
    }
}

//Using in Controller for exemple
class Users {
  use UserRepository;
  use Users;
  
  public function findUsers() {
    //This will be and instance of UserRepository, class responsable to provide methods of User Object.
    $usersRepository = new UserRepository(new Users);
    
    //This will return an instance of Users.
    $users = $usersRepository->findUsers();

  }
}

```

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
                ->setInnerJoin(['table' => 'homens'], ['table' => 'mulheres'] )
                ->setWhere('homens.name = "João"');

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
This method is responsable to check if a table name was seted in the instance of class that heirded this class
```php
  public function getTableName()
```

This method is responsable to set a table in the instance of class that heirded this class
```php
  public function setTable(string $tableName)
```

This method is responsable to set the Where Simple Conditction for the query of instance class that heirded this class
```php
  public function setWhere(string $condition)
```

This method is responsable to set the Where In Conditction for the query of instance class that heirded this class
```php
  public function setWhereIn(string $column, array $options)
```

This method is responsable to concat the Inner Join command
```php
  public function setInnerJoin(array $joinedTable, array $newTableJoin)
```

This method is responsable to concat the Right Join command
```php
  public function setRightJoin(array $joinedTable, array $newTableJoin)
```

This method is responsable to concat the Left Join command
```php
  public function setLeftJoin(array $joinedTable, array $newTableJoin)
```

This method is responsable to concat the Full Join command
```php
  public function setFullJoin(array $newTableJoin)
```

This method is responsable to concat the Cross Join command
```php
  public function setCrossJoin(array $newTableJoin)
```


## Select
### DataBase\Actions\DML\Commands\Select
Set the distinct option, by default it is false
```php
  public function setDistinct(bool $requiereDistinct)
```

Set the fields option, by default its '*'
```php
  public function setFields(array $fields)
```

Set the limit option
```php
  public function setLimit(int $limit, int $offset = 0)
```

Set the order option, ASC or DESC
```php
  public function setOrder(string $fields, string $order = "ASC")
```

Set the group by option.
```php
  public function setGroupBy(array $fields)
```

Set the having option
```php
  public function setHaving(string $condition)
```

Build the query sentense for a Select query
```php
  public function buildQuery(bool $subquery = false)
```
 
This method id responsable to return data with an associative array
```php
  public function fetchAssoc(bool $returnAll = false)
```

This method is responsable to return data with an object of a class type. By default it is stdClass
```php
  public function fetchObject(bool $returnAll = false, string $class = stdClass::class)
```



## Insert
### DataBase\Actions\DML\Commands\Insert

Set the ignore option, by default it is false
```php
  public function setIgnore(bool $requiereIgnore)
```

Set the fields option, by default it is false
```php
  public function setFields(array $fields)
```

Set the values option, by default it is false
```php
  public function setValues(array $values)
```

Set the values with a SELECT query
```php
  public function setInsertSelect(string $query)
```
 
Build the query sentense for a Insert query
```php
  public function buildQuery()
```

This method is responsable to run the query of the class without a fetch result.
```php
  public function runQuery()
```



## Update
### DataBase\Actions\DML\Commands\Update
This method is responsable for set a SET command for an Update query
```php
  public function setSet(array $columnValue)
```

Build the query sentense for a Update query
```php
  public function buildQuery()
```
 
This method is responsable to run the query of the class without a fetch result.
```php
  public function runQuery()
```


## Delete
### DataBase\Actions\DML\Commands\Delete
Build the query sentense for a delete query
```php
  public function buildQuery()
```

This method is responsable to run the query of the class without a fetch result.
```php
  public function runQuery()
```

# About Project
If you dont't have a php environment with a database, you can use this docker environment: 
https://github.com/PatrickBarreto/baseBackend

The next step of this code is:
- Implement the join ressource
- Implement the essentials DDL Commands (CREATE, DROP, TRUNCATE, ALTER)

The object with this project is have a simple data base handler, with the essentials commands to help developers with a pontual features. 
The projetc will be openSource and will be published at https://packagist.org/

If you have an idea or pull request to make, do it. Ideas are very welcome.


# How to Use
```php

use DataBase\Crud;
use DataBase\Actions\DML\Commands\Select;
use DataBase\Query;

1 Way
/////// Use the Crud Instance

$crudTeste = new Crud('tableName');

//Use de Insert Command with a Crud Class
$crudTeste->insert->setFields(['nome'])->setValues(["pedro"])->runQuery();

//Use de Update Command with a Crud Class
$crudTeste->update->setSet(['nome'=>'joão'])->setWhere('nome = "Pedro"')->runQuery();

//Use de Select Command with a Crud Class
$crudTeste->select->fetchObject(true);



2 Way
//////// Use the command Instance

$select = new Select;
$select->setTable('tableName');
$select->setWhereIn('nome', ['pedro', 'joão', 'josé']);
$select->fetchAssoc(true);



3 Way
/////// Use the Query Class
Be carefull, here you controll what will be running. The application won't do nothing but execute the query.

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

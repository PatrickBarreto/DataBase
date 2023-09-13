<?php

namespace DataBase\Actions\DML\Commands;

use DataBase\Actions\DML\DML;

/**
 * Class responsable to be a Select query constructor
 */
class Select extends DML {
  
    public bool $distinct = false;  
    public string $fields = "*";
    public string $limit = '';
    public string $order = '';
    public string $group = '';
    public string $having = '';

    /**
     * Set the distinct option, by default it is false
     *
     * @param boolean $requiereDistinct
     * @return Select
     */
    public function setDistinct(bool $requiereDistinct) {
        $this->distinct = $requiereDistinct;
        return $this;
    }

    /**
     * Set the fields option, by default its '*'
     *
     * @param array $fields
     * @return Select
     */
    public function setFields(array $fields) {
        $this->fields = implode(",",$fields).' ';
        return $this;
    }

    /**
     * Set the limit option
     *
     * @param integer $limit
     * @param integer $offset
     * @return Select
     */
    public function setLimit(int $limit, int $offset = 0) {
        $this->limit = "LIMIT "."{$offset},{$limit}";
        return $this;
    }

    /**
     * Set the order option, ASC or DESC
     *
     * @param string $fields
     * @param string $order
     * @return Select
     */
    public function setOrder(string $fields, string $order = "ASC") {
        $this->order = "ORDER BY "."{$fields} {$order}";
        return $this;
    }

    /**
     * Set the group by option.
     *
     * @param array $fields
     * @return Select
     */
    public function setGroupBy(array $fields) {
        $this->group = "GROUP BY ".implode(",",$fields);
        return $this;
    }

    /**
     * Set the having option
     *
     * @param string $condition
     * @return Select
     */
    public function setHaving(string $condition) {
        $this->having = "HAVING ".$condition;
        return $this;
    }

    /**
     * Build the query sentense for a Select query
     *
     * @return Select
     */
    public function buildQuery(bool $subquery = false) {
        $distinct = ($this->distinct) ? "DISTINCT " : null;
        $table    = $this->getTableName();

        $query = "SELECT {$distinct} {$this->fields} FROM {$table} {$this->join} {$this->where} {$this->whereIn} {$this->limit} {$this->order} {$this->group} {$this->having}";
        
        if($subquery){
            $query = "({$query})";
        }
        $this->query = $query;
        return $this;
    }

}
<?php

namespace App\Actions\Commands;

use App\Actions\ActionsDML;

class Select extends ActionsDML{

    public bool $distinct = false;
    public string $fields = "*";
    public string $limit;
    public string $order;
    public string $group;
    public string $having ;

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
        $this->fields = implode(",",$fields);
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
        $this->limit = "{$offset},{$limit}";
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
        $this->order = "{$fields} {$order}";
        return $this;
    }

    /**
     * Set the groupby option.
     *
     * @param array $fields
     * @return Select
     */
    public function setGroupBy(array $fields) {
        $this->fields = implode(",",$fields);
        return $this;
    }

    /**
     * set the having option
     *
     * @param string $condition
     * @return Select
     */
    public function setHaving(string $condition) {
        $this->fields = $condition;
        return $this;
    }

}
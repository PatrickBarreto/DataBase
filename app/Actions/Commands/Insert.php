<?php

namespace App\Actions\Commands;

use App\Actions\ActionsDML;

class Insert extends ActionsDML{
     public bool $ignore = false;
     public string $fields;
     public array $values; 

     /**
     * Set the ignore option, by default it is false
     *
     * @param boolean $requiereIgnore
     * @return Insert
     */
     public function setIgnore(bool $requiereIgnore) {
          $this->ignore = $requiereIgnore;
          return $this;
     }

     /**
     * Set the fields option, by default it is false
     *
     * @param array $fields
     * @return Insert
     */
     public function setFields(array $fields) {
          $this->fields = implode(",",$fields);
          return $this;
     }

     /**
     * Set the values option, by default it is false
     *
     * @param array $values
     * @return Insert
     */
     public function setValues(array $values) {
          $this->fields = implode(",",$values);
          return $this;
     }

   



}
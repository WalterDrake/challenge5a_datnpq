<?php

/**
 * main model
 */
class Model extends Database
{
    public $error = array();
    public function __construct()
    {
        // Set the table name
        $this->table = $this->table ?? strtolower(static::class) . 's';
    }
    // Get all records from the table using where clause
    public function where($column, $value)
    {
        $column = addslashes($column);
        $query = "SELECT * FROM $this->table WHERE $column = :value";
        return $this->query($query, [':value' => $value]);
    }

    public function findAll()
    {
        $query = "SELECT * FROM $this->table";
        return $this->query($query);
    }

    public function insert($data)
    {
        // remove unwanted columns
        if(property_exists($this, 'allowedColumns')){
            foreach($data as $key => $value){
                if(!in_array($key, $this->allowedColumns)){
                    unset($data[$key]);
                }
            }
         }

        // run functions before insert
        if(property_exists($this, 'beforeInsert')){
           foreach($this->beforeInsert as $func){
               $data = $this->$func($data);
           }
        }

        $keys = array_keys($data);
        $columns = implode(',', $keys);
        $values =  implode(',:', $keys);
        $query = "INSERT INTO $this->table ($columns) VALUES (:$values)";
        return $this->query($query, $data);
    }

    public function update($id, $data){
        $str = '';

        // $data = ['avatar' => 'admin.jpg'];
        foreach($data as $key => $value){
            $str .= $key . ' = :' . $key . ',';
        }
        $str = trim($str, ',');
        $query = "UPDATE $this->table SET $str WHERE id = :id";
        $data['id'] = $id;
        return $this->query($query, $data);
    }

    
    public function delete($id){
        $query = "DELETE FROM $this->table WHERE id = :id";
        return $this->query($query, [':id' => $id]);
    }
}

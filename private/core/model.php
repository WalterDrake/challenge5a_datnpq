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

    // return the column name of the primary key
    protected function get_primary_key($table)
    {

        $query = "SHOW KEYS from $table WHERE Key_name = 'PRIMARY' ";
        $db = new Database();
        $data = $db->query($query);

        if (!empty($data[0])) {
            return $data[0]->Column_name;
        }
        return 'id';
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
        if (property_exists($this, 'allowedColumns')) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        // run functions before insert
        if (property_exists($this, 'beforeInsert')) {
            foreach ($this->beforeInsert as $func) {
                $data = $this->$func($data);
            }
        }

        $keys = array_keys($data);
        $columns = implode(',', $keys);
        $values =  implode(',:', $keys);
        $query = "INSERT INTO $this->table ($columns) VALUES (:$values)";
        return $this->query($query, $data);
    }

    public function update($id, $data)
    {

        // remove unwanted columns
        if (property_exists($this, 'allowedColumns')) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        // run functions before update
        if (property_exists($this, 'beforeUpdate')) {
            foreach ($this->beforeUpdate as $func) {
                $data = $this->$func($data);
            }
        }

        $str = '';

        // $data = ['avatar' => 'admin.jpg'];
        foreach ($data as $key => $value) {
            $str .= $key . ' = :' . $key . ',';
        }
        $str = trim($str, ',');
        $query = "UPDATE $this->table SET $str WHERE id = :id";
        $data['id'] = $id;
        return $this->query($query, $data);
    }


    public function delete($id)
    {
        $query = "DELETE FROM $this->table WHERE id = :id";
        return $this->query($query, [':id' => $id]);
    }

    // return the first record from the table
    public function first($column, $value, $orderby = 'desc')
    {

        $column = addslashes($column);
        $primary_key = $this->get_primary_key($this->table);
        $query = "select * from $this->table where $column = :value order by $primary_key $orderby";
        $data = $this->query($query, [
            'value' => $value
        ]);

        // return the first record
        if (is_array($data)) {
            $data = $data[0];
        }
        return $data;
    }
}

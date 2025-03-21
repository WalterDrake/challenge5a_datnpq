<?php

/**
 * Database connection
 */
class Database
{
    // Check if the connection is already established
    private function connect()
    {
        $string = DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME;
        if (!$con = new PDO($string, DB_USER, DB_PASS)) {
            die("Failed to connect to database");
        }
        return $con;
    }

    // Run the query
    public function query($query, $data = array(), $data_type = "object")
    {
        $con = $this->connect();
        $stmt = $con->prepare($query);
        if ($stmt) {
            $check = $stmt->execute($data);
            if ($check) {
                if ($data_type == 'object') {
                    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                if (is_array($data) && count($data) > 0) {
                    return $data;
                }
            }
        }
        return false;
    }


}

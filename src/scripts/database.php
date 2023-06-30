<?php

class Database
{
    private $connection;

    public  $database_name;
    public  $servername;
    private $user_login;
    private $user_name;

    function __construct() {
        global $environment;

        $this->database_name = $environment->access("database_name");
        $this->servername    = $environment->access("servername");
        $this->user_login    = $environment->access("user_login");
        $this->user_name     = $environment->access("user_name");
    }

    // === METHODS ===
    public function connect(): int {
        $this->connection = new mysqli($this->servername,
                                       $this->user_name,
                                       $this->user_login,
                                       $this->database_name);
        return 0;
    }

    private function query(string $query) {
        return $this->connection->query($query);
    }

    // === ACTIONS ===
    public function select(string $table_name, array $columns) {
        // SELECT column1, column2, ... FROM table_name;
        $sql_query = "SELECT %s FROM %s;";

        $str_columns = $this->format_one_dimensional($columns, false);

        $this->query(sprintf($sql_query, $str_columns, $table_name));
    }

    public function insert(string $table_name, array $data) {
        // INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
        $sql_query = "INSERT INTO %s (%s) VALUES (%s);";

        $str_columns = $this->format_one_dimensional(array_keys($data), false);
        $str_values = $this->format_one_dimensional(array_values($data), true);

        $this->query(sprintf($sql_query, $table_name, $str_columns, $str_values));
    }

    public function delete(string $table_name, array $conditions) {
        // DELETE FROM table_name WHERE condition;
        $sql_query = "DELETE FROM %s WHERE %s;";

        $str_conditions = $this->format_two_dimensional($conditions);

        $this->query(sprintf($sql_query, $table_name, $str_conditions));
    }

    // === FORMATING ===
    private function format_one_dimensional(array $array, bool $strict = true): string {
        $new_array = [];
        foreach($array as $row) {
            if($strict) {
                if(is_string($row)) {
                    $row = $this->secure($row);
                    array_push($new_array, "'".$row."'");
                } else {
                    array_push($new_array, $row);
                }
            } else {
                array_push($new_array, $row);
            }
        }
        return implode(", ", $new_array);
    }

    private function format_two_dimensional(array $array): string {
        $new_array = [];
        foreach($array as $key => $value) {
            if(is_string($value)) {
                $value = $this->secure($value);
                array_push($new_array, $key."='".$value."'");
            } else {
                array_push($new_array, $key."=".$value);
            }
        }
        return implode(", ", $new_array);
    }

    // === SECRUITY AND THREAT PREVENTION ===
    private function secure(string $string): string {
        $string = prevent_corss_site_scripting($string);
        $string = prevent_sql_injection($string);
        return $string;
    }

    private function prevent_corss_site_scripting(string $string): string {
        return $string;
    }

    private function prevent_sql_injection(string $string): string {
        return $string;
    }
}

?>

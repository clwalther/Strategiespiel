<?php

class Database
{
    private $connection;

    public  $database_name;
    public  $servername;
    private $user_login;
    private $user_name;

    function __construct() {
        // environment variables
        $this->database_name = "WIZARD_SCHOOLS";
        $this->servername    = "localhost";
        $this->user_login    = "Et76ESefdCzTzkHHeSJxrexePDbC8m";
        $this->user_name     = "PjFrmotTq";
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

    public function close(): int {
        return $this->connection->close();
    }

    // === ACTIONS ===
    public function select(string $table_name, array $columns): array {
        // SELECT column1, column2, ... FROM table_name;
        $sql_query = "SELECT %s FROM %s;";

        $str_columns = $this->format_one_dimensional($columns, false);

        $query_response = $this->query(sprintf($sql_query, $str_columns, $table_name));
        return $this->format_query_response($query_response);
    }

    public function select_where(string $table_name, array $columns, array $conditions): array {
        // SELECT column1, column2, ... FROM table_name WHERE condition;
        $sql_query = "SELECT %s FROM %s WHERE %s;";

        $str_columns = $this->format_one_dimensional($columns, false);
        $str_conditions = $this->format_two_dimensional($conditions);

        $query_response = $this->query(sprintf($sql_query, $str_columns, $table_name, $str_conditions));
        return $this->format_query_response($query_response);
    }

    public function insert(string $table_name, array $data) {
        // INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
        $sql_query = "INSERT INTO %s (%s) VALUES (%s);";

        $str_columns = $this->format_one_dimensional(array_keys($data), false);
        $str_values = $this->format_one_dimensional(array_values($data), true);

        $this->query(sprintf($sql_query, $table_name, $str_columns, $str_values));
    }

    public function update(string $table_name, array $data, array $conditions) {
        // UPDATE table_name SET column1 = value1, column2 = value2, ... WHERE condition;
        $sql_query = "UPDATE %s SET %s WHERE %s;";

        $str_data = $this->format_two_dimensional($data);
        $str_conditions = $this->format_two_dimensional($conditions);

        $this->query(sprintf($sql_query, $table_name, $str_data, $str_conditions));
    }

    public function delete(string $table_name, array $conditions) {
        // DELETE FROM table_name WHERE condition;
        $sql_query = "DELETE FROM %s WHERE %s;";

        $str_conditions = $this->format_two_dimensional($conditions);

        $this->query(sprintf($sql_query, $table_name, $str_conditions));
    }

    public function delete_all(string $table_name) {
        // DELETE FROM table_name;
        $sql_query = "DELETE FROM %s;";

        $this->query(sprintf($sql_query, $table_name));
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

    private function format_query_response(object $query_response): array {
        if($query_response->num_rows > 0) {
            $results = [];
            while($signle_result = $query_response->fetch_assoc()) {
                array_push($results, $signle_result);
            }
            return $results;
        } else {
            return [];
        }
    }

    // === SECRUITY AND THREAT PREVENTION ===
    private function secure(string $string): string {
        $string = htmlspecialchars($string);
        $string = str_replace("&", "&amp", $string);
        $string = str_replace(";", "&#59;", $string);

        $string = $this->prevent_corss_site_scripting($string);
        $string = $this->prevent_sql_injection($string);
        return $string;
    }

    private function prevent_corss_site_scripting(string $string): string {
        $string = str_replace("<", "&lt;", $string);
        $string = str_replace(">", "&gt;", $string);
        $string = str_replace("/", "&#47;", $string);
        return $string;
    }

    private function prevent_sql_injection(string $string): string {
        $string = str_replace('"', "&quot;", $string);
        $string = str_replace("'", "&apos;", $string);
        $string = str_replace("`", "&#96;", $string);
        $string = str_replace("´", "&acute;", $string);
        $string = str_replace("-", "&#45;", $string);
        return $string;
    }
}

?>

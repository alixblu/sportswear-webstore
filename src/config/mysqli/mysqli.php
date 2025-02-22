<?php
class ConfigMysqli {
    public static function connectDatabase() {
        include './database/database.php';
        $host = $configDatabase['db_host'];
        $user = $configDatabase['db_user'];
        $pass = $configDatabase['db_pass'];
        $database = $configDatabase['db_database'];
        
        $conn = new mysqli($host, $user, $pass, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        return $conn;
    }
}
?>

<?php
class ConfigMysqli {
    public static function connectDatabase() {
        include dirname(__FILE__) . '/../../database/database.php';
        $conn = new mysqli($configDatabase['db_host'], $configDatabase['db_user'], $configDatabase['db_pass'], $configDatabase['db_database']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>
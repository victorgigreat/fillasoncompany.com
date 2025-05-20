<?php
function get_db_connection() {
    $host = 'localhost';
    $db = 'cylgtjtg_store';
    $user = 'cylgtjtg_store';
    $pass = 'cylgtjtg_store';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
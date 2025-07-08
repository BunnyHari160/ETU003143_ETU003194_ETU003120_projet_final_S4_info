<?php
function getDB() {
    // $host = 'localhost';
    // $dbname = 'db_s2_ETU003194';
    // $username = 'ETU003194';
    // $password = 'GBRhnWw6';

    $host = 'localhost';
    $dbname = 'tp_flight';
    $username = 'root';
    $password = '';

    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die(json_encode(['error' => $e->getMessage()]));
    }
}

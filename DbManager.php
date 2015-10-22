<?php
function getDb(){
    $dsn = 'pgsql:host=localhost port=5432 dbname=workspace_database user=postgres';
    $usr = 'postgres';
    
    try {
    $db = new PDO($dsn, $usr);
    $db->exec('SET NAMES utf8');
} catch (PDOException $e) {
    die ("接続エラー: {$e ->getMessage()}");
    }
    return $db;
}
?>
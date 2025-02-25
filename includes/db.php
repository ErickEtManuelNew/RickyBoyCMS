<?php
require __DIR__ . '/../config/config.php';

/**
 * Summary of query
 * @param mixed $sql
 * @param mixed $params
 * @return bool|PDOStatement
 */
function query($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

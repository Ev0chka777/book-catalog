<?php
// Параметры подключения к БД
$host     = 'localhost';
$port     = '5432';
$dbname   = 'book_catalog';
$user     = 'postgres';
$password = 'huesos25'; 

// DSN строка подключения
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $password);

    // Включить выбрасывание исключений при ошибках SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // По умолчанию выбирать строки как ассоциативные массивы
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Ошибка подключения к БД: ' . $e->getMessage());
}
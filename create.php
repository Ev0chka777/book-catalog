<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die('Доступ запрещён.');
}
require 'db.php';

//data from (POST)
$title =trim($_POST['title'] ??'');
$author =trim($_POST['author'] ??'');
$year =$_POST['year'] !==''? (int)$_POST['year'] : null;
$genre =trim($_POST['genre'] ??'');
$descripsion =trim($_POST['description'] ??'');

//min validation
if ($title === '' || $author ==='') {
    die ('Название и автор обязательны');
}

//img auto from placehold
$cover_url = 'https://placehold.co/400x600/2c3e50/ffffff?text=' . urlencode($title);

//podgotovlennii zapros
$sql ='INSERT INTO books (title, author, year, genre, description, cover_url)
VALUES (:title, :author, :year, :genre, :description, :cover_url)';

$stmt =$pdo->prepare($sql);
$stmt->execute([
    'title' =>$title,
    'author'=>$author,
    'year' =>$year,
    'genre' =>$genre,
    'description' =>$description,
    'cover_url' =>$cover_url,
]);

//back to dashboard
header('Location: index.php');
exit;
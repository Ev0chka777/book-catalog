<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !=='admin'){
    http_response_code(403);
    die('Доступ запрещён');
}
require 'db.php';

//if it is POST - obnovlenie BD 
if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
    $id =(int)$_POST['id'];
    $title =trim($_POST['title'] ??'');
    $author =trim($_POST['author'] ?? '');
    $year =$_POST['year'] !==''?
    (int)$_POST['year']:null;
    $genre =trim($_POST['genre'] ?? '');
    $description =trim($_POST['description'] ?? '');

    if($title ===''|| $author === '') {
        die('Название и автор обязательны.');
    }
    
    $sql = 'UPDATE books
    SET title = :title,
    author = :author,
    year = :year,
    genre = :genre,
    description = :description
    WHERE id =:id';
    $stmt =$pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'title' => $title,
        'author' => $author,
        'year' => $year,
        'genre' => $genre,
        'description' => $description,
    ]);

    header('Location: index.php');
    exit;
}

//if GET -install book for form
$id = (int) ($_GET['id'] ??0);
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

if (!$book) {
    die('Книга не найдена');
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Изменить книгу - Каталог</title>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Каталог книг</a>
</div>
</nav>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Изменить книгу</h2>

                    <form action="edit.php" method="POST">
                        <input type="hidden" name="id" value="<?=(int) $book['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" class="form-control" name="title"
                            value="<?= htmlspecialchars($book['title']) ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Автор</label>
    <input type="text" class="form-control" name="author"
    value="<?= htmlspecialchars($book['author']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Год</label>
<input type="number" class="form-control" name="year"
value="<?= (int)$book['year'] ?>"
min="1" max="2100">
</div>

<div class="mb-3">
    <label class="form-label">Жанр</label>
    <input type="text" class="form-control" name="genre"
    value="<?= htmlspecialchars($book['genre']) ?>"> 
</div>
<div class="mb-3">
    <label class="form-label">Описание</label>
    <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($book['description']) ?></textarea>
</div>

<div class="d-flex gap-2 justify-content-end">
    <a href="index.php" class="btn btn-secondary">Отмена</a>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</div>
</form>

</div>
</div>
</div>
</div>
</main>

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


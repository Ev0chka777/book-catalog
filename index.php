<?php
session_start();
require 'db.php';

//import books from BD
$stmt =$pdo->query('SELECT * FROM books ORDER BY id');
$books =$stmt->fetchALL();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css"
>

    <title>Каталог книг</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Каталог книг</a>
            <div class="d-flex gap-2 align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text text-light me-2">
                    Привет, <?=htmlspecialchars($_SESSION['username'])
                    ?>!
                    </span>
                    <a href="logout.php" class="btn btn-outline-light">Выйти</a>
                    <?php else: ?>
                <a href="login.php" class="btn btn-outline-light">Войти</a>
                <a href="register.php" class="btn btn-outline-light">Регистрация</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">Добавить книгу</button> 
                <?php endif;?>
            </div>
        </div>
    </nav>
  <main class="container m-4">
    <h1 class="mb-4">Моя библиотека</h1>
        <div class="row g-4">
            <?php foreach ($books as $book): ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100">
                    <img src="<?= htmlspecialchars($book['cover_url']) ?>"
                    class="card-img-top"
                    alt="<?= htmlspecialchars($book['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($book['author']) ?>, <?=(int)$book['year'] ?>
                     </p>
                        <span class="badge bg-secondary mb-2"> <?= htmlspecialchars($book['genre']) ?>
                        </span>
                        <p class="card-text"><?= htmlspecialchars($book['description']) ?></p>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <div class="d-flex gap-2">
                        <a href="edit.php?id=<?= (int)$book['id'] ?>" class="btn btn-outline-primary btn-sm">Изменить</a>
                        <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Точно удалить книгу?');">
                            <input type="hidden" name="id" value="<?=(int)$book['id'] ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Удалить</button>
            </form>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
  </main>
  <footer class="bg-dark text-light py-4 mt-5 text-center">
    <p class="mb-0">&copy; 2026 Каталог книг</p>
</footer>
  <div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Добавить книгу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="create.php" method="POST">
                    <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Автор</label>
                        <input type="text" class="form-control" name="author" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Год</label>
                        <input type="number" class="form-control" name="year" min="1" max="2100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Жанр</label>
                        <select class="form-select" name="genre">
                        <option>Роман</option>
                        <option>Фантастика</option>
                        <option>Антиутопия</option>
                        <option>Магический реализм</option>
                        <option>Детектив</option>
                        <option>Поэзия</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
            </form>
        </div>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
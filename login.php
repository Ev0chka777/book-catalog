<?php
require 'db.php';

$error ='';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email =trim($_POST['email'] ??'');
    $password =$_POST['password'] ??'';

    if($email === '' || $password ==='') {
        $error='Введите email и пароль.';
    } else {
        //искать пользователя по почте
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :e');
        $stmt->execute(['e'=> $email]);
        $user =$stmt->fetch();

        if ($user && password_verify ($password, $user['password_hash'])) {
            //верный пароль -создать сессию
            session_start();
            $_SESSION['user_id'] =$user['id'];
            $_SESSION['username'] =$user['username'];
            $_SESSION['role'] =$user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error ='Неверный email или пароль.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Каталог книг</title>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Каталог книг</a>
        </div>
    </nav>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-4">

                        <h2 class="card-title text-center mb-4">Вход</h2>
                        <?php if ($error !==''): ?>
                            <div class="alert alert-danger"><?=htmlspecialchars($error)?><div>
                                <?php endif;?>

                    <form action="login.php" method="POST">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Запомнить меня</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Войти</button>
                            </div>
                        </form>
                                <p class="text-center mt-4 mb-0">Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
                                </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
 <script
 src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>   
<footer class="bg-dark text-light py-4 mt-5 text-center">
    <p class="mb-0">&copy; 2026 Каталог книг</p>
</footer>
</body>
</html>
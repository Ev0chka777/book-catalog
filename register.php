<?php
require 'db.php';
$error = ''; //peeremennaya for soobshenie ob oshibke
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username =trim($_POST['username'] ??'');
    $email =trim($_POST['email'] ??'');
    $password =$_POST['password'] ?? '';
    $password_confirm =$_POST['password_confirm'] ?? '';

    //validation
    if ($username === '' || $email === '' || $password === '')
        {
            $error ='Все поля обязательны.';
        } elseif ($password !== $password_confirm) {
            $error ='Пароли не совпадают.';
        } elseif (strlen($password) <6) {
            $error='Пароль должен быть не короче 6 символов';
} else {
    //Are username and email free?
    $stmt=$pdo->prepare('SELECT id FROM users WHERE username = :u OR email = :e');
    $stmt->execute(['u'=>$username, 'e' =>$email]);
    if ($stmt->fetch()){
        $error ='Имя пользователя или email уже заняты.';
    } else{
        //Хэш password
        $hash=password_hash($password, PASSWORD_DEFAULT);
        $stmt =$pdo->prepare(
            'INSERT INTO users (username, email, password_hash) VALUES (:u, :e, :h)'
        );
        $stmt->execute(['u'=>$username, 'e'=> $email, 'h' => $hash]);

        //Auto login after register
        session_start();
        $user_id=$pdo->lastInsertId();
        $_SESSION['user_id'] =$user_id;
        $_SESSION['username'] =$username;
        $_SESSION['role'] ='user';
        header('Location: index.php');
        exit;
    }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Каталог книг</title>
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

                        <h2 class="card-title text-center mb-4">Регистрация</h2>
                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Имя пользователя</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">Подтверждение пароля</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Зарегистрироваться</button>
                            </div>
                        </form>
                                <p class="text-center mt-4 mb-0">Есть аккаунт?<a href="login.html">Войти</a>
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
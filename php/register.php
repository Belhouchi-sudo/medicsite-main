<?php
require_once __DIR__ . '/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO authentication (username,password) VALUES (:u,:p)');
        try {
            $stmt->execute([':u'=>$username,':p'=>$hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: admin/dashboard.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Username may already exist.';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title>
<link href="../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="max-width:480px;margin:40px auto;">
  <h3>Create Account</h3>
  <?php if (!empty($error)) echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; ?>
  <form method="post">
    <div class="mb-3"><label>Username</label><input class="form-control" name="username" required></div>
    <div class="mb-3"><label>Password</label><input class="form-control" type="password" name="password" required></div>
    <button class="btn btn-primary" type="submit">Register</button>
    <a class="btn btn-link" href="login.php">Login</a>
  </form>
</div>
</body></html>

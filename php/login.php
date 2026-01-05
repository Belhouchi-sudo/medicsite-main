<?php
require_once __DIR__ . '/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM authentication WHERE username = :u LIMIT 1');
        $stmt->execute([':u'=>$username]);
        $row = $stmt->fetch();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header('Location: admin/dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title>
<link href="../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="max-width:480px;margin:40px auto;">
  <h3>Login</h3>
  <?php if (!empty($error)) echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; ?>
  <form method="post">
    <div class="mb-3"><label>Username</label><input class="form-control" name="username" required></div>
    <div class="mb-3"><label>Password</label><input class="form-control" type="password" name="password" required></div>
    <button class="btn btn-primary" type="submit">Login</button>
    <a class="btn btn-link" href="register.php">Create account</a>
  </form>
</div>
</body></html>

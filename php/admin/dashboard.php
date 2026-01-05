<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Dashboard</title>
<link href="../../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="max-width:1000px;margin:30px auto;">
  <h2>Admin Dashboard</h2>
  <p>Welcome. <a href="manage_appointments.php" class="btn btn-primary">Manage Appointments</a>
  <a class="btn btn-secondary" href="../logout.php">Logout</a></p>
</div>
</body></html>

<?php
require_once __DIR__ . '/../connection.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header('Location: manage_appointments.php'); exit; }

if (isset($_GET['confirm']) && $_GET['confirm'] === '1') {
    // delete record and file
    $stmt = $pdo->prepare('SELECT medical_file FROM appointments WHERE id = :id');
    $stmt->execute([':id'=>$id]);
    $r = $stmt->fetch();
    if ($r && $r['medical_file']) {
        $f = __DIR__ . '/../uploads/' . $r['medical_file'];
        if (is_file($f)) @unlink($f);
    }
    $del = $pdo->prepare('DELETE FROM appointments WHERE id = :id');
    $del->execute([':id'=>$id]);
    header('Location: manage_appointments.php'); exit;
}

?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Confirm Delete</title>
<link href="../../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="max-width:600px;margin:30px auto;">
  <h4>Confirm Delete</h4>
  <p>Are you sure you want to delete this appointment request?</p>
  <a class="btn btn-danger" href="delete_appointment.php?id=<?=$id?>&confirm=1">Yes, delete</a>
  <a class="btn btn-secondary" href="manage_appointments.php">Cancel</a>
</div>
</body></html>

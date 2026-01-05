<?php
require_once __DIR__ . '/../connection.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
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
}

header('Location: manage_appointments.php');
exit;
?>

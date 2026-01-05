<?php
require_once __DIR__ . '/../connection.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM appointments WHERE id = :id');
$stmt->execute([':id'=>$id]);
$row = $stmt->fetch();
if (!$row) { echo 'Not found.'; exit; }
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Appointment Details</title>
<link href="../../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="max-width:800px;margin:30px auto;">
  <h3>Appointment Details</h3>
  <dl class="row">
    <?php foreach ($row as $k=>$v): ?>
      <?php if (in_array($k,['id','created_at'])) continue; ?>
      <dt class="col-sm-4"><?=htmlspecialchars(str_replace('_',' ',ucwords($k,'_'))) ?></dt>
      <dd class="col-sm-8"><?php if ($k==='medical_file' && $v): ?><a href="../uploads/<?=rawurlencode($v)?>" download>Download file</a>
        <?php else echo nl2br(htmlspecialchars($v)); endif; ?></dd>
    <?php endforeach; ?>
  </dl>
  <p><a href="manage_appointments.php" class="btn btn-link">Back</a></p>
</div>
</body></html>
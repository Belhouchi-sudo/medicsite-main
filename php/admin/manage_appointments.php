<?php
require_once __DIR__ . '/../connection.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$stmt = $pdo->query('SELECT id, first_name, last_name, requested_service, preferred_date, medical_file FROM appointments ORDER BY created_at DESC');
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Manage Appointments</title>
<link href="../../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="margin:30px auto;max-width:1100px;">
  <h3>Manage Appointments</h3>
  <table class="table table-striped">
    <thead><tr><th>First Name</th><th>Last Name</th><th>Requested Service</th><th>Preferred Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['first_name'])?></td>
        <td><?=htmlspecialchars($r['last_name'])?></td>
        <td><?=htmlspecialchars($r['requested_service'])?></td>
        <td><?=htmlspecialchars($r['preferred_date'])?></td>
        <td>
          <a class="btn btn-sm btn-info" href="appointment_details.php?id=<?=$r['id']?>">Details</a>
          <a class="btn btn-sm btn-warning" href="edit_appointment.php?id=<?=$r['id']?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete_appointment.php?id=<?=$r['id']?>" onclick="return confirm('Are you sure you want to delete this appointment request?');">Delete</a>
          <?php if ($r['medical_file']): ?>
            <a class="btn btn-sm btn-secondary" href="../uploads/<?=rawurlencode($r['medical_file'])?>" download>Download File</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="dashboard.php" class="btn btn-link">Back to Dashboard</a></p>
</div>
</body></html>

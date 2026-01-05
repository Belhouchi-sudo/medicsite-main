<?php
require_once __DIR__ . '/../connection.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM appointments WHERE id = :id');
$stmt->execute([':id'=>$id]);
$row = $stmt->fetch();
if (!$row) { echo 'Not found.'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $requested_service = trim($_POST['requested_service'] ?? '');
    $preferred_date = $_POST['preferred_date'] ?? null;
    $preferred_time = $_POST['preferred_time'] ?? null;
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $allergies = trim($_POST['allergies_history'] ?? '');
    $selected_doctor = trim($_POST['selected_doctor'] ?? '');

    // handle optional file upload
    $uploaded = $row['medical_file'];
    if (!empty($_FILES['medical_file']) && $_FILES['medical_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['medical_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf','jpg','jpeg','png'])) {
            $uploadDir = __DIR__ . '/../uploads';
            if (!is_dir($uploadDir)) mkdir($uploadDir,0755,true);
            $basename = bin2hex(random_bytes(8)).'.'.$ext;
            if (move_uploaded_file($file['tmp_name'], $uploadDir.'/'.$basename)) {
                $uploaded = $basename;
            }
        }
    }

    $upd = $pdo->prepare('UPDATE appointments SET first_name=:first,last_name=:last,requested_service=:service,preferred_date=:pdate,preferred_time=:ptime,email=:email,phone=:phone,address=:address,allergies_history=:allergies,selected_doctor=:doctor,medical_file=:file WHERE id=:id');
    $upd->execute([
        ':first'=>$first_name,':last'=>$last_name,':service'=>$requested_service,':pdate'=>$preferred_date,':ptime'=>$preferred_time,':email'=>$email,':phone'=>$phone,':address'=>$address,':allergies'=>$allergies,':doctor'=>$selected_doctor,':file'=>$uploaded,':id'=>$id
    ]);
    header('Location: manage_appointments.php'); exit;
}

?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit Appointment</title>
<link href="../../css/style.css" rel="stylesheet"></head><body>
<div class="container" style="max-width:720px;margin:30px auto;">
  <h3>Edit Appointment</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3"><label>First name</label><input class="form-control" name="first_name" value="<?=htmlspecialchars($row['first_name'])?>"></div>
    <div class="mb-3"><label>Last name</label><input class="form-control" name="last_name" value="<?=htmlspecialchars($row['last_name'])?>"></div>
    <div class="mb-3"><label>Requested service</label><input class="form-control" name="requested_service" value="<?=htmlspecialchars($row['requested_service'])?>"></div>
    <div class="mb-3"><label>Preferred date</label><input type="date" class="form-control" name="preferred_date" value="<?=htmlspecialchars($row['preferred_date'])?>"></div>
    <div class="mb-3"><label>Preferred time</label><input type="time" class="form-control" name="preferred_time" value="<?=htmlspecialchars($row['preferred_time'])?>"></div>
    <div class="mb-3"><label>Email</label><input class="form-control" name="email" value="<?=htmlspecialchars($row['email'])?>"></div>
    <div class="mb-3"><label>Phone</label><input class="form-control" name="phone" value="<?=htmlspecialchars($row['phone'])?>"></div>
    <div class="mb-3"><label>Address</label><input class="form-control" name="address" value="<?=htmlspecialchars($row['address'])?>"></div>
    <div class="mb-3"><label>Allergies / history</label><textarea class="form-control" name="allergies_history"><?=htmlspecialchars($row['allergies_history'])?></textarea></div>
    <div class="mb-3"><label>Doctor</label><input class="form-control" name="selected_doctor" value="<?=htmlspecialchars($row['selected_doctor'])?>"></div>
    <div class="mb-3"><label>Medical file (upload to replace)</label><input type="file" name="medical_file" class="form-control"></div>
    <button class="btn btn-primary" type="submit">Save</button>
    <a class="btn btn-link" href="manage_appointments.php">Cancel</a>
  </form>
</div>
</body></html>

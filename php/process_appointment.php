<?php
require_once __DIR__ . '/connection.php';

function clean($v){ return trim(htmlspecialchars($v, ENT_QUOTES, 'UTF-8')); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../appointment.html');
    exit;
}

$errors = [];

$first_name = isset($_POST['first_name']) ? clean($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? clean($_POST['last_name']) : '';
$birthdate = !empty($_POST['birthdate']) ? $_POST['birthdate'] : null;
$gender = isset($_POST['gender']) ? clean($_POST['gender']) : null;
$requested_service = isset($_POST['requested_service']) ? clean($_POST['requested_service']) : '';
$preferred_date = !empty($_POST['preferred_date']) ? $_POST['preferred_date'] : null;
$preferred_time = !empty($_POST['preferred_time']) ? $_POST['preferred_time'] : null;
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? clean($_POST['phone']) : null;
$address = isset($_POST['address']) ? clean($_POST['address']) : null;
$allergies = isset($_POST['allergies_history']) ? clean($_POST['allergies_history']) : null;
$selected_doctor = isset($_POST['selected_doctor']) ? clean($_POST['selected_doctor']) : null;

// Basic validation
if ($first_name === '') $errors[] = 'First name is required.';
if ($last_name === '') $errors[] = 'Last name is required.';
if ($requested_service === '') $errors[] = 'Requested service is required.';
if ($preferred_date === null) $errors[] = 'Preferred date is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';

$uploadedFileName = null;
// Handle file upload
if (!empty($_FILES['medical_file']) && $_FILES['medical_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['medical_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload error.';
    } else {
        $allowed = ['pdf','jpg','jpeg','png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Allowed file types: PDF, JPG, PNG.';
        } else {
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $basename = bin2hex(random_bytes(8)) . '.' . $ext;
            $destination = $uploadDir . '/' . $basename;
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                $errors[] = 'Failed to move uploaded file.';
            } else {
                $uploadedFileName = $basename;
            }
        }
    }
}

if (count($errors) > 0) {
    echo '<h3>There were errors:</h3><ul>';
    foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>';
    echo '</ul><p><a href="../appointment.html">Go back</a></p>';
    exit;
}

// Insert into database
$stmt = $pdo->prepare("INSERT INTO appointments
    (first_name,last_name,birthdate,gender,requested_service,preferred_date,preferred_time,email,phone,address,allergies_history,selected_doctor,medical_file)
    VALUES (:first,:last,:birthdate,:gender,:service,:pdate,:ptime,:email,:phone,:address,:allergies,:doctor,:file)");

$stmt->execute([
    ':first' => $first_name,
    ':last' => $last_name,
    ':birthdate' => $birthdate,
    ':gender' => $gender,
    ':service' => $requested_service,
    ':pdate' => $preferred_date,
    ':ptime' => $preferred_time,
    ':email' => $email,
    ':phone' => $phone,
    ':address' => $address,
    ':allergies' => $allergies,
    ':doctor' => $selected_doctor,
    ':file' => $uploadedFileName
]);

// Success message
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Appointment Submitted</title>
  <link href="/css/style.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="max-width:700px;margin:40px auto;">
    <div class="alert alert-success">Your appointment request has been submitted successfully.</div>
    <p><a href="../index.html">Return to home</a></p>
  </div>
</body>
</html>

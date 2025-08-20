
<?php
// Connect to database
include 'connection.php';

// Get POST data
$uid    = isset($_POST['uid']) ? $_POST['uid'] : '';
$name   = isset($_POST['name']) ? $_POST['name'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

if ($uid && $name && $status) {
    $stmt = $conn->prepare("INSERT INTO attendance_rfid (uid, name, status, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $uid, $name, $status);
    if ($stmt->execute()) {
        echo "Attendance recorded";
    } else {
        echo "Database error";
    }
    $stmt->close();
} else {
    echo "Invalid data";
}
?>
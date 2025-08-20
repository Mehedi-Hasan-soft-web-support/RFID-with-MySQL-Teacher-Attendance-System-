<?php
include 'connection.php';

$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password']; // No hashing — plain text

$sql = "INSERT INTO login (name, email, username, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $username, $password);

if ($stmt->execute()) {
    echo "<script>alert('Signup successful! You can now login.'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Signup failed. Try a different username or email.'); window.location.href='signup.php';</script>";
}

$stmt->close();
$conn->close();
?>

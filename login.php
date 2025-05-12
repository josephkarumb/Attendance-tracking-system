<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "1234";
$db = "attendancetrackingsystem";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username']; // Getting the username from the form
$password = $_POST['password']; // Getting the password from the form

// Use prepared statements to prevent SQL injection
$sql = "SELECT * FROM Admins WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);  // Bind the username parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Fetch the user's details
    $row = $result->fetch_assoc();
    
    // Debugging: Print stored password
    echo "Stored password: " . $row['password'];  // This shows the stored password in the DB for debugging
    
    // Compare the plain text password
    if ($password == $row['password']) {
        // Password is correct, log the user in
        $_SESSION['admin'] = $username;
        header("Location: dashboard.html"); // Redirect to dashboard if successful
        exit();
    } else {
        // Password is incorrect
        echo "<script>alert('Invalid login'); window.location='index.html';</script>";
    }
} else {
    // No such user found
    echo "<script>alert('Invalid login'); window.location='index.html';</script>";
}

$conn->close();
?>

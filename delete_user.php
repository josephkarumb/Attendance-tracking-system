<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_number'])) {
  $id_number = $_POST['id_number'];

  // Get user ID, image path, and embedding path from the database
  $query = "SELECT Users.id, Users.image_path, Users.embedding_path 
            FROM Users 
            LEFT JOIN attendance ON Users.id = attendance.user_id 
            WHERE Users.id_number = ?";

  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $id_number);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $image_path = $user['image_path'];
    $embedding_path = $user['embedding_path'];

    // Delete attendance records
    $conn->query("DELETE FROM Attendance WHERE user_id = $user_id");


    // Delete user record
    $conn->query("DELETE FROM Users WHERE id = $user_id");

    // Delete image file
    if (!empty($image_path) && file_exists($image_path)) {
      unlink($image_path);
    }

    // Delete embedding file
    if (!empty($embedding_path) && file_exists($embedding_path)) {
      unlink($embedding_path);
    }

    echo "<script>
      alert('User and all associated data deleted successfully.');
      window.location.href='view_registered.php'; // Replace with your users page
    </script>";
  } else {
    echo "<script>
      alert('User not found.');
      window.location.href='view_registered.php';
    </script>";
  }

  $stmt->close();
}

$conn->close();
?>

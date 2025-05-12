<?php
include('db_connect.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Attendance</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dashboard">
    <h2>All Attendance Logs</h2>

    <?php
    // Update the SQL query to join Attendance with Users
    $sql = "
    SELECT Attendance.*, Users.role, Users.name
    FROM Attendance
    JOIN Users ON Attendance.user_id = Users.id";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>No</th><th>Name</th><th>Role</th><th>Check-In</th><th>Check-Out</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['name'] . "</td><td>" . $row['role'] . "</td><td>" . $row['check_in_time'] . "</td><td>" . $row['check_out_time'] . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "No attendance logs found.";
    }
    ?>
  </div>
</body>
</html>

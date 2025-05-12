<?php
include('db_connect.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Visitors Attendance</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dashboard">
    <h2>Visitors Attendance Logs</h2>
<form method="post" action="export_visitor_attendance.php">
  <button type="submit" style="margin-bottom: 20px;">Export to Document</button>
</form>
    <?php
    $sql = "
     SELECT Attendance.*, Users.role, Users.name, Users.image_path, Users.id_number, Users.phone_number
    FROM Attendance
    JOIN Users ON Attendance.user_id = Users.id
    WHERE Users.role = 'visitor'";    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>No</th><th>Image</th><th>Name</th><th>ID_number</th><th>Phone_number</th><th>Check-In</th><th>Check-Out</th></tr>";
      $counter = 1;
      while($row = $result->fetch_assoc()) {
        $base_url = "http://localhost/AttendanceTrackingSystem/attendance_backend/REFERENCE_IMAGES_FOLDER/";
        $image_filename = basename($row['image_path']);
        $image = !empty($row['image_path']) ? $base_url . $image_filename : 'default.jpg';

        echo "<tr>";
        echo "<td>" . $counter++ . "</td>";
        echo "<td><img src='" . $image . "' alt='User Image'></td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['id_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['check_in_time']) . "</td>";
        echo "<td>" . htmlspecialchars($row['check_out_time']) . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p class='no-records'>No attendance logs found.</p>";
    }
    ?>
  </div>
</body>
</html>

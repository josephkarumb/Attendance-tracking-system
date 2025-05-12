<?php
include('db_connect.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Staffs Attendance</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid #ccc;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 50%;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Staffs Attendance Logs</h2>
<form method="post" action="export_staff_attendance.php">
  <button type="submit" style="margin-bottom: 20px;">Export to Document</button>
</form>
    
    <?php
   $sql = "
   SELECT Attendance.*, Users.role, Users.name, Users.image_path
   FROM Attendance
   JOIN Users ON Attendance.user_id = Users.id
   WHERE Users.role = 'staff'";
   
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>No</th><th>Image</th><th>Name</th><th>Check-In</th><th>Check-Out</th></tr>";
      $count = 1;
      while($row = $result->fetch_assoc()) {
        $base_url = "http://localhost/AttendanceTrackingSystem/attendance_backend/REFERENCE_IMAGES_FOLDER/";
        $image_filename = basename($row['image_path']);
        $image = !empty($row['image_path']) ? $base_url . $image_filename : 'default.jpg';
      
        echo "<tr>";
        echo "<td>" . $count++ . "</td>";
        echo "<td><img src='" . htmlspecialchars($image) . "' alt='User Image'></td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['check_in_time']) . "</td>";
        echo "<td>" . htmlspecialchars($row['check_out_time']) . "</td>";
        echo "</tr>";
      }
      
      echo "</table>";
    } else {
      echo "No attendance logs found.";
    }
    ?>
  </div>
</body>
</html>

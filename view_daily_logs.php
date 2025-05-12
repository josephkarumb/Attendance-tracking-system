<?php
include('db_connect.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Attendance</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .form-box {
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      width: 60%;
    }
    .form-box select, .form-box input[type="date"] {
      padding: 8px;
      margin-right: 10px;
    }
    .form-box button {
      padding: 8px 12px;
    }
    .file-links {
      margin-top: 20px;
    }
    table {
      border-collapse: collapse;
      width: 80%;
      margin-top: 20px;
    }
    th, td {
      padding: 8px 12px;
      border: 1px solid #ddd;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Daily Attendance Logs</h2>

    <!-- Form to select type and date -->
    <div class="form-box">
      <form method="GET">
        <label>Type:</label>
        <select name="type" required>
          <option value="">-- Select --</option>
          <option value="staff">Staff</option>
          <option value="visitors">Visitors</option>
        </select>
        <label>Date:</label>
        <input type="date" name="date" required>
        <button type="submit">View Logs</button>
      </form>
    </div>

    <?php
    // If form submitted
    if (isset($_GET['type']) && isset($_GET['date'])) {
      $type = $_GET['type'];
      $date = $_GET['date'];

      // Use correct file prefix
      $prefix = $type === 'staff' ? 'Staff' : 'Visitor'; // Singular
      $filename = $prefix . "_Attendance_" . $date . ".csv";
      $filepath = "exports/$type/$filename";

      echo "<div class='file-links'>";
      if (file_exists($filepath)) {
        echo "<p><strong>Log File:</strong> <a href='$filepath' download>$filename</a></p>";
      } else {
        echo "<p style='color:red;'>No log file found for <strong>$type</strong> on <strong>$date</strong>.</p>";
      }
      echo "</div>";
    }
    ?>

    <hr>

    <!-- Today's logs -->
    <h3>Today's Attendance Summary</h3>
    <?php
    $sql = "
      SELECT Attendance.*, Users.role, Users.name
      FROM Attendance
      JOIN Users ON Attendance.user_id = Users.id
      WHERE DATE(Attendance.check_in_time) = CURDATE()";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>Name</th><th>Role</th><th>Check-In</th><th>Check-Out</th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['role']}</td>
                <td>{$row['check_in_time']}</td>
                <td>{$row['check_out_time']}</td>
              </tr>";
      }
      echo "</table>";
    } else {
      echo "No attendance logs found for today.";
    }
    ?>
  </div>
</body>
</html>

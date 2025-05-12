<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registered Users</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .delete-button {
      background-color: red;
      color: white;
      padding: 5px 10px;
      border: none;
      cursor: pointer;
    }
    .delete-button:hover {
      background-color: darkred;
    }
    img {
      height: 50px;
      width: 50px;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Registered Users</h2>
    <table>
      <tr>
        <th>#</th>
        <th>Image</th>
        <th>Name</th>
        <th>ID Number</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Date Joined</th>
        <th>Action</th>
      </tr>
      <?php
      $sql = "SELECT name, id_number, phone_number, role, date_joined, image_path FROM Users";
      $result = $conn->query($sql);
      $count = 1;

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $base_url = "http://localhost/AttendanceTrackingSystem/attendance_backend/REFERENCE_IMAGES_FOLDER/";
          $image_filename = basename($row['image_path']);
          $image = !empty($row['image_path']) ? $base_url . $image_filename : 'default.jpg';
          
          echo "<tr>
                  <td>{$count}</td>
                  <td><img src='{$image}' alt='User Image'></td>
                  <td>{$row['name']}</td>
                  <td>{$row['id_number']}</td>
                  <td>{$row['phone_number']}</td>
                  <td>{$row['role']}</td>
                  <td>{$row['date_joined']}</td>
                  <td>
                    <form action='delete_user.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this user?');\">
                      <input type='hidden' name='id_number' value='{$row['id_number']}'>
                      <button type='submit' class='delete-button'>Delete</button>
                    </form>
                  </td>
                </tr>";
          $count++;
        }
      } else {
        echo "<tr><td colspan='8' class='no-records'>No records found</td></tr>";
      }

      $conn->close();
      ?>
    </table>
  </div>
</body>
</html>

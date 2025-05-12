<?php
include('db_connect.php');

// Get today's date
$date = date('Y-m-d');
$filename = "Visitor_Attendance_$date.csv";
$folder = "exports/visitors";
$filepath = $folder . "/" . $filename;

// Ensure the folder exists
if (!is_dir($folder)) {
  mkdir($folder, 0777, true);
}

// Open file in write mode to avoid duplicates
$file = fopen($filepath, "w");

// Write UTF-8 BOM
fwrite($file, "\xEF\xBB\xBF");

// Write Title Row (merged-style text in first column)
// Write Title Row (simulated merged-style across columns)
$title = ["LAW COURT $date ATTENDANCE LOG", "", "", "", "", ""];
fputcsv($file, $title);


// Write an empty row after title for spacing
fputcsv($file, []);

// Write CSV headers
$headers = ["#", "Name", "ID Number", "Phone Number", "Check-In", "Check-Out"];
fputcsv($file, $headers);

// Fetch today's staff attendance
$sql = "
SELECT Attendance.*, Users.name, Users.id_number, Users.phone_number
FROM Attendance
JOIN Users ON Attendance.user_id = Users.id
WHERE Users.role = 'visitor' AND DATE(Attendance.check_in_time) = '$date'";

$result = $conn->query($sql);
$counter = 1;

// Write rows
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $line = [
      $counter++,
      $row['name'],
      "'" . $row['id_number'],
      "'" . $row['phone_number'], // force phone as string
      $row['check_in_time'],
      $row['check_out_time']
    ];
    fputcsv($file, $line);
  }
}

fclose($file);

// Redirect back with success message
echo "<script>
  alert('Attendance exported to $filename!');
  window.location.href='visitors_attendance.php';
</script>";
?>

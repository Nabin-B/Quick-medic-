<?php
$conn = new mysqli("localhost", "root", "", "category_info");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Check if a date was selected
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

$sql = "SELECT a.id, a.patient_id, d.name AS doctor_name, h.name AS hospital_name, 
               a.appointment_date, a.created_at
        FROM appointments a
        JOIN doctor d ON a.doctor_id = d.id
        JOIN hospital h ON a.hospital_id = h.id";

if($filter_date != ''){
    $sql .= " WHERE a.appointment_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filter_date);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
table { width:100%; border-collapse:collapse; }
th, td { padding:10px; border:1px solid #ddd; text-align:left; }
th { background:#2563eb; color:#fff; }
tr:hover { background:#f1f5f9; }
</style>
</head>
<body>
<h2>Appointments</h2>
<table border="1" cellpadding="10">
<tr>
<th>ID</th><th>Patient ID</th><th>Doctor Name</th><th>Hospital Name</th><th>Date</th><th>Created date and Time</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['patient_id']}</td>
                <td>{$row['doctor_name']}</td>
                <td>{$row['hospital_name']}</td>
                <td>{$row['appointment_date']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
}else{
    echo "<tr><td colspan='7'>No appointments found.</td></tr>";
}
$conn->close();
?>
</table>

</body>
</html>

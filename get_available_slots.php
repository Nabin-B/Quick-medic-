<?php
$conn = new mysqli("localhost", "root", "", "category_info");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$doctor_id = $_GET['doctor_id'] ?? null;
$date = $_GET['date'] ?? null;

if(!$doctor_id || !$date){ 
    echo "<option value=''>-- No slots --</option>"; 
    exit; 
}

// Fetch slots for this doctor and day of week
$dayOfWeek = date('l', strtotime($date));

$stmt = $conn->prepare("SELECT id, start_time, end_time 
                        FROM doctor_availability 
                        WHERE doctor_id=? AND day_of_week=? AND is_booked=0");
$stmt->bind_param("is", $doctor_id, $dayOfWeek);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows==0){ 
    echo "<option value=''>-- No slots --</option>"; 
    exit; 
}

while($row = $res->fetch_assoc()){
    $slot_id = $row['id'];
    $slot_time = $row['start_time']; // show start time as option
    echo "<option value='$slot_id'>$slot_time</option>";
}

$stmt->close();
$conn->close();
?>

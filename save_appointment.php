<?php
session_start();
$conn = new mysqli("localhost", "root", "", "category_info");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Get form data
$doctor_id   = $_POST['doctor'] ?? null;
$hospital_id = $_POST['hospital'] ?? null;
$slot_id     = $_POST['appointment_time'] ?? null; // slot_id is sent via select

// For testing, patient_id = 1
$patient_id = 1;

if ($patient_id && $doctor_id && $hospital_id && $slot_id) {

    // 1️⃣ Check if the slot exists and is available
    $stmt = $conn->prepare("SELECT day_of_week, start_time FROM doctor_availability WHERE id=? AND doctor_id=? AND is_booked=0");
    $stmt->bind_param("ii", $slot_id, $doctor_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows === 0){
        die("⚠ This slot is already booked or invalid.");
    }

    $slot = $res->fetch_assoc();
    $appt_date = date('Y-m-d'); // you can set dynamically from a form if needed
    $appt_time = $slot['start_time'];

    $conn->begin_transaction();

    try {
        // 2️⃣ Mark slot as booked
        $update = $conn->prepare("UPDATE doctor_availability SET is_booked=1 WHERE id=?");
        $update->bind_param("i", $slot_id);
        $update->execute();

        // 3️⃣ Insert appointment
        $insert = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, hospital_id, appointment_date, appointment_time, created_at)
                                  VALUES (?, ?, ?, ?, ?, NOW())");
        $insert->bind_param("iiiss", $patient_id, $doctor_id, $hospital_id, $appt_date, $appt_time);
        $insert->execute();

        $conn->commit();
        echo "<script>alert('✅ Appointment booked for $appt_date at $appt_time!'); window.location='categoryinterface.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();

} else {
    echo "⚠ Missing required data.";
}

$conn->close();
?>

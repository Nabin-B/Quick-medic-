<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "category_info");

// Get district_id from URL (AJAX request)
$hospital_id = $_GET['hospital_id'];

// Query all malls where district_id matches
$result = $conn->query("SELECT * FROM medical_field WHERE hospital_id = $hospital_id");

// First option (default)
echo "<option value=''>-- Select medical field --</option>";

// Loop through malls and create <option>
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>

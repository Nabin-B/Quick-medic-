<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "category_info");

// Get district_id from URL (AJAX request)
$medical_field_id = $_GET['medical_field_id'];

// Query all malls where district_id matches
$result = $conn->query("SELECT * FROM doctor WHERE medical_field_id = $medical_field_id");

// First option (default)
echo "<option value=''>-- Select doctor --</option>";

// Loop through malls and create <option>
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>

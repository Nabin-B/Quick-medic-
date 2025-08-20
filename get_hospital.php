<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "category_info");

// Get district_id from URL (AJAX request)
$district_id = $_GET['district_id'];

// Query all malls where district_id matches
$result = $conn->query("SELECT * FROM hospital WHERE district_id = $district_id");

// First option (default)
echo "<option value=''>-- Select hospital --</option>";

// Loop through malls and create <option>
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
?>

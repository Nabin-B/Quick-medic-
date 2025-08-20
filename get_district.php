<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "category_info");

// Get province_id from URL (AJAX request) i.e /get_district.php?province_id=2
$province_id = $_GET['province_id'];

// Query all districts where province_id matches
$result = $conn->query("SELECT * FROM district WHERE province_id = $province_id");

// First option (default)
echo "<option value=''>-- Select District --</option>";

// Loop through districts and create <option>
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";// echo will return the available districts in the form of option tag
}
?>

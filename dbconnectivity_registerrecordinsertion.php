<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "category_info");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from form
$name = $_POST['name'];
$age = $_POST['age'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$gender = $_POST['gender'];
$username = $_POST['username'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO patients(name, age, address, contact, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("sisssss", $name, $age, $address, $contact, $gender, $username, $hashed_password);

// Execute the query
if ($stmt->execute()) {
    $inserted_id = $stmt->insert_id;
    echo "<script>alert('Registration successful! Your ID is: $inserted_id'); window.location.href='2secondpage.html';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>

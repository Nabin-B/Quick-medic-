<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "category_info");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input from the form
$username = $_POST['username'];
$password = $_POST['password'];

//First check if it's admin
if ($username === "admin" && $password === "admin") {
    header("Location: admin.html");
    exit();
}

// Otherwise, check normal users in database
$stmt = $conn->prepare("SELECT password FROM patients WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
        // Success: redirect to user page
        header("Location: user.html");
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>

<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "student_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$first_name = $_POST['First_Name'];
$last_name = $_POST['Last_Name'];
$dob = $_POST['dob'];
$email = $_POST['Email_Id'];
$lrn = $_POST['Mobile_Number'];
$gender = $_POST['Gender'];
$address = $_POST['Address'];
$city = $_POST['City'];
$pin_code = $_POST['Pin_Code'];
$state = $_POST['State'];
$country = "Philippines"; // Fixed value

// Handle hobbies
$hobbies = [];
if (isset($_POST['Hobby_Drawing'])) $hobbies[] = "Drawing";
if (isset($_POST['Hobby_Singing'])) $hobbies[] = "Singing";
if (isset($_POST['Hobby_Dancing'])) $hobbies[] = "Dancing";
if (isset($_POST['Hobby_Cooking'])) $hobbies[] = "Cooking";
if (!empty($_POST['Other_Hobby'])) $hobbies[] = $_POST['Other_Hobby'];
$hobbies_str = implode(", ", $hobbies);

// Handle course selection
$course = isset($_POST['Course']) ? $_POST['Course'] : '';

// Insert into database
$sql = "INSERT INTO students (first_name, last_name, dob, email, lrn, gender, address, city, pin_code, state, country, hobbies, course)
        VALUES ('$first_name', '$last_name', '$dob', '$email', '$lrn', '$gender', '$address', '$city', '$pin_code', '$state', '$country', '$hobbies_str', '$course')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Registration Successful!'); window.location.href='index.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>

<?php
// Include database connection
require_once 'db.php';

// Start session
session_start();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $first_name = sanitize_input($conn, $_POST['First_Name']);
    $last_name = sanitize_input($conn, $_POST['Last_Name']);
    $dob = sanitize_input($conn, $_POST['dob']);
    $email = sanitize_input($conn, $_POST['Email_Id']);
    $lrn = sanitize_input($conn, $_POST['Mobile_Number']);
    $gender = sanitize_input($conn, $_POST['Gender']);
    $address = sanitize_input($conn, $_POST['Address']);
    $city = sanitize_input($conn, $_POST['City']);
    $pin_code = sanitize_input($conn, $_POST['Pin_Code']);
    $state = sanitize_input($conn, $_POST['State']);
    $country = sanitize_input($conn, $_POST['Country']);
    
    // Handle hobbies
    $hobbies = isset($_POST['Hobby']) ? implode(", ", $_POST['Hobby']) : "";
    $other_hobby = sanitize_input($conn, $_POST['Other_Hobby']);
    
    $course = sanitize_input($conn, $_POST['Course']);
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, dob, email, lrn, gender, address, city, pin_code, state, country, hobbies, other_hobby, course) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssssssss", $first_name, $last_name, $dob, $email, $lrn, $gender, $address, $city, $pin_code, $state, $country, $hobbies, $other_hobby, $course);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #001a33;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        h3 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        form {
            background-color: #002b4d;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 90%;
        }
        
        table {
            width: 100%;
        }
        
        td {
            padding: 10px;
            vertical-align: top;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 5px;
        }
        
        input[type="submit"],
        input[type="reset"] {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            margin: 5px 0;
        }
        
        input[type="submit"] {
            background-color: #00994d;
            color: white;
        }
        
        input[type="reset"] {
            background-color: #e60000;
            color: white;
        }
        
        input[type="submit"]:hover {
            background-color: #007a3d;
        }
        
        input[type="reset"]:hover {
            background-color: #cc0000;
        }
        
        .admin-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: #004080;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .admin-btn:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>
    <a href="admin_login.php" class="admin-btn">Admin Login</a>
    
    <h3>STUDENT REGISTRATION FORM</h3>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <table align="center" cellpadding="12">
            <!-- First Name -->
            <tr>
                <td>FIRST NAME</td>
                <td><input type="text" name="First_Name" maxlength="30" placeholder="Enter first name" required /></td>
            </tr>

            <!-- Last Name -->
            <tr>
                <td>LAST NAME</td>
                <td><input type="text" name="Last_Name" maxlength="30" placeholder="Enter last name" required /></td>
            </tr>

            <!-- Date of Birth -->
            <tr>
                <td>DATE OF BIRTH</td>
                <td>
                    <input type="date" name="dob" required />
                </td>
            </tr>

            <!-- Email -->
            <tr>
                <td>EMAIL ID</td>
                <td><input type="email" name="Email_Id" maxlength="100" placeholder="Enter email" required /></td>
            </tr>

            <!-- LRN -->
            <tr>
                <td>LRN</td>
                <td><input type="text" name="Mobile_Number" maxlength="12" placeholder="Enter LRN" required /></td>
            </tr>

            <!-- Gender -->
            <tr>
                <td>GENDER</td>
                <td>
                    <input type="radio" name="Gender" value="Male" required /> Male
                    <input type="radio" name="Gender" value="Female" required /> Female
                </td>
            </tr>

            <!-- Address -->
            <tr>
                <td>ADDRESS</td>
                <td><textarea name="Address" rows="4" cols="30" placeholder="Enter address" required></textarea></td>
            </tr>

            <!-- City -->
            <tr>
                <td>CITY</td>
                <td><input type="text" name="City" maxlength="30" placeholder="Enter city" required /></td>
            </tr>

            <!-- Pin Code -->
            <tr>
                <td>PIN CODE</td>
                <td><input type="text" name="Pin_Code" maxlength="6" placeholder="Enter pin code" required /></td>
            </tr>

            <!-- State -->
            <tr>
                <td>STATE</td>
                <td><input type="text" name="State" maxlength="30" placeholder="Enter state" required /></td>
            </tr>

            <!-- Country -->
            <tr>
                <td>COUNTRY</td>
                <td><input type="text" name="Country" value="Philippines" readonly /></td>
            </tr>

            <!-- Hobbies -->
            <tr>
                <td>HOBBIES</td>
                <td>
                    <input type="checkbox" name="Hobby[]" value="Drawing" /> Drawing
                    <input type="checkbox" name="Hobby[]" value="Singing" /> Singing
                    <input type="checkbox" name="Hobby[]" value="Dancing" /> Dancing
                    <input type="checkbox" name="Hobby[]" value="Cooking" /> Cooking
                    <br />
                    Others: <input type="text" name="Other_Hobby" maxlength="30" placeholder="Specify other hobbies" />
                </td>
            </tr>

            <!-- Course Applied For -->
            <tr>
                <td>COURSES APPLIED FOR</td>
                <td>
                    <input type="radio" name="Course" value="BSME" required /> BSME
                    <input type="radio" name="Course" value="BSIT" required /> BSIT
                    <input type="radio" name="Course" value="BSCS" required /> BSCS
                    <input type="radio" name="Course" value="BSIS" required /> BSIS
                </td>
            </tr>

            <!-- Submit & Reset -->
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Submit">
                    <input type="reset" value="Reset">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
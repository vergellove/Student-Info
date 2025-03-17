<?php
// Include database connection
require_once 'db.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_students.php");
    exit;
}

$id = intval($_GET['id']);
$message = '';
$messageType = '';

// Process form submission for updates
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
    
    // Update student data
    $sql = "UPDATE students SET 
            first_name = ?, 
            last_name = ?, 
            dob = ?, 
            email = ?, 
            lrn = ?, 
            gender = ?, 
            address = ?, 
            city = ?, 
            pin_code = ?, 
            state = ?, 
            country = ?, 
            hobbies = ?, 
            other_hobby = ?, 
            course = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", $first_name, $last_name, $dob, $email, $lrn, $gender, $address, $city, $pin_code, $state, $country, $hobbies, $other_hobby, $course, $id);
    
    if ($stmt->execute()) {
        $message = "Student information updated successfully!";
        $messageType = "success";
    } else {
        $message = "Error updating record: " . $stmt->error;
        $messageType = "error";
    }
    
    $stmt->close();
}

// Get current student data
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: view_students.php");
    exit;
}

$student = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Information</title>
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
            padding: 20px;
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
            width: 100%;
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
        input[type="reset"],
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            margin: 5px 0;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        
        input[type="submit"] {
            background-color: #00994d;
            color: white;
        }
        
        input[type="reset"], .btn-cancel {
            background-color: #e60000;
            color: white;
        }
        
        .btn-back {
            background-color: #4da6ff;
            color: white;
        }
        
        input[type="submit"]:hover {
            background-color: #007a3d;
        }
        
        input[type="reset"]:hover {
            background-color: #cc0000;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            width: 100%;
            max-width: 800px;
            text-align: center;
        }
        
        .success {
            background-color: #00994d;
            color: white;
        }
        
        .error {
            background-color: #e60000;
            color: white;
        }
        
        .hobbies-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .hobby-item {
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <?php if (!empty($message)): ?>
        <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <h3>EDIT STUDENT INFORMATION</h3>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>" method="POST">
        <table align="center" cellpadding="12">
            <!-- First Name -->
            <tr>
                <td>FIRST NAME</td>
                <td><input type="text" name="First_Name" maxlength="30" placeholder="Enter first name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required /></td>
            </tr>

            <!-- Last Name -->
            <tr>
                <td>LAST NAME</td>
                <td><input type="text" name="Last_Name" maxlength="30" placeholder="Enter last name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required /></td>
            </tr>

            <!-- Date of Birth -->
            <tr>
                <td>DATE OF BIRTH</td>
                <td>
                    <input type="date" name="dob" value="<?php echo $student['dob']; ?>" required />
                </td>
            </tr>

            <!-- Email -->
            <tr>
                <td>EMAIL ID</td>
                <td><input type="email" name="Email_Id" maxlength="100" placeholder="Enter email" value="<?php echo htmlspecialchars($student['email']); ?>" required /></td>
            </tr>

            <!-- LRN -->
            <tr>
                <td>LRN</td>
                <td><input type="text" name="Mobile_Number" maxlength="12" placeholder="Enter LRN" value="<?php echo htmlspecialchars($student['lrn']); ?>" required /></td>
            </tr>

            <!-- Gender -->
            <tr>
                <td>GENDER</td>
                <td>
                    <input type="radio" name="Gender" value="Male" <?php if ($student['gender'] == 'Male') echo 'checked'; ?> required /> Male
                    <input type="radio" name="Gender" value="Female" <?php if ($student['gender'] == 'Female') echo 'checked'; ?> required /> Female
                </td>
            </tr>

            <!-- Address -->
            <tr>
                <td>ADDRESS</td>
                <td><textarea name="Address" rows="4" cols="30" placeholder="Enter address" required><?php echo htmlspecialchars($student['address']); ?></textarea></td>
            </tr>

            <!-- City -->
            <tr>
                <td>CITY</td>
                <td><input type="text" name="City" maxlength="30" placeholder="Enter city" value="<?php echo htmlspecialchars($student['city']); ?>" required /></td>
            </tr>

            <!-- Pin Code -->
            <tr>
                <td>PIN CODE</td>
                <td><input type="text" name="Pin_Code" maxlength="6" placeholder="Enter pin code" value="<?php echo htmlspecialchars($student['pin_code']); ?>" required /></td>
            </tr>

            <!-- State -->
            <tr>
                <td>STATE</td>
                <td><input type="text" name="State" maxlength="30" placeholder="Enter state" value="<?php echo htmlspecialchars($student['state']); ?>" required /></td>
            </tr>

            <!-- Country -->
            <tr>
                <td>COUNTRY</td>
                <td><input type="text" name="Country" value="Philippines" readonly /></td>
            </tr>

            <!-- Hobbies -->
            <tr>
                <td>HOBBIES</td>
                <td class="hobbies-container">
                    <?php 
                    $hobbies_array = explode(", ", $student['hobbies']);
                    $hobby_options = ['Drawing', 'Singing', 'Dancing', 'Cooking'];
                    foreach ($hobby_options as $hobby): 
                    ?>
                    <span class="hobby-item">
                        <input type="checkbox" name="Hobby[]" value="<?php echo $hobby; ?>" <?php if (in_array($hobby, $hobbies_array)) echo 'checked'; ?> /> 
                        <?php echo $hobby; ?>
                    </span>
                    <?php endforeach; ?>
                    <br />
                    Others: <input type="text" name="Other_Hobby" maxlength="30" placeholder="Specify other hobbies" value="<?php echo htmlspecialchars($student['other_hobby']); ?>" />
                </td>
            </tr>

            <!-- Course Applied For -->
            <tr>
                <td>COURSES APPLIED FOR</td>
                <td>
                    <input type="radio" name="Course" value="BSME" <?php if ($student['course'] == 'BSME') echo 'checked'; ?> required /> BSME
                    <input type="radio" name="Course" value="BSIT" <?php if ($student['course'] == 'BSIT') echo 'checked'; ?> required /> BSIT
                    <input type="radio" name="Course" value="BSCS" <?php if ($student['course'] == 'BSCS') echo 'checked'; ?> required /> BSCS
                    <input type="radio" name="Course" value="BSIS" <?php if ($student['course'] == 'BSIS') echo 'checked'; ?> required /> BSIS
                </td>
            </tr>

            <!-- Submit & Buttons -->
            <tr>
            <td colspan="2" align="center">
        <input type="submit" value="Update">
        <input type="reset" value="Reset" class="btn-cancel">
        <input type="button" value="Back to List" class="btn-back" onclick="window.location.href='view_students.php'" style="padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; margin: 5px 0; text-align: center;">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
<?php $conn->close(); ?>
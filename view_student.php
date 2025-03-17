<?php
// Include database connection
require_once 'db.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_students.php");
    exit;
}

$id = intval($_GET['id']);

// Get student data
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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #001a33;
            color: white;
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #002b4d;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 10px 15px;
            background-color: #00994d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        
        .student-info {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 15px;
        }
        
        .info-label {
            font-weight: bold;
            width: 200px;
            color: #4da6ff;
        }
        
        .info-value {
            flex-grow: 1;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
        
        .btn-danger {
            background-color: #e60000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Student Details</h1>
            <a href="view_students.php" class="btn">Back to List</a>
        </div>
        
        <div class="student-info">
            <div class="info-row">
                <div class="info-label">Student ID:</div>
                <div class="info-value"><?php echo $student['id']; ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value"><?php echo date('F d, Y', strtotime($student['dob'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['email']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">LRN:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['lrn']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Gender:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['gender']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['address']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">City:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['city']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">PIN Code:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['pin_code']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">State:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['state']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Country:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['country']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Hobbies:</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($student['hobbies']); ?>
                    <?php if (!empty($student['other_hobby'])): ?>
                        <br>Other: <?php echo htmlspecialchars($student['other_hobby']); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Course:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['course']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Registration Date:</div>
                <div class="info-value"><?php echo date('F d, Y g:i A', strtotime($student['reg_date'])); ?></div>
            </div>
        </div>
        
        <div class="actions">
            <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn">Edit</a>
            <a href="view_students.php?delete_id=<?php echo $student['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
        </div>
    </div>
</body>
</html>
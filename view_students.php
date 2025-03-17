<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Include database connection
require_once 'db.php';

// Delete student if requested
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM students WHERE id = ?";
    
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "Student deleted successfully";
    } else {
        $message = "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

// Search functionality
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = sanitize_input($conn, $_GET['search']);
    $sql = "CALL search_students(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    // Get all students from the database
    $sql = "SELECT * FROM students ORDER BY id DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
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
            max-width: 1200px;
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
        
        .btn-danger {
            background-color: #e60000;
        }
        
        .search-form {
            display: flex;
            margin-bottom: 20px;
        }
        
        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
        }
        
        .search-form button {
            padding: 8px 15px;
            background-color: #00994d;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #004080;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        tr:nth-child(even) td {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .message {
            background-color: #00994d;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .error {
            background-color: #e60000;
        }
        
        .actions {
            display: flex;
            gap: 5px;
        }
        
        .no-records {
            text-align: center;
            padding: 20px;
            font-style: italic;
        }
        
        .admin-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #004080;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .admin-info {
            display: flex;
            align-items: center;
        }
        
        .admin-info span {
            margin-left: 10px;
            font-weight: bold;
        }
        
        .logout-btn {
            background-color: #e60000;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 15px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        
        .logout-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-bar">
            <div class="admin-info">
                <span>Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <div class="header">
            <h1>Registered Students</h1>
            <a href="index.php" class="btn">Add New Student</a>
        </div>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form class="search-form" method="GET" action="">
            <input type="text" name="search" placeholder="Search by name, email, LRN, or course" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>LRN</th>
                        <th>Gender</th>
                        <th>Course</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['lrn']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo date('M d, Y g:i A', strtotime($row['reg_date'])); ?></td>
                            <td class="actions">
                                <a href="view_student.php?id=<?php echo $row['id']; ?>" class="btn">View</a>
                                <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                                <a href="view_students.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-records">No student records found</div>
        <?php endif; ?>
    </div>
</body>
</html>
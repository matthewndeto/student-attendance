<?php
include 'db_connection.php';

$message = '';
$student = null;

// Check if student ID is provided
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    
    // Get student information
    $sql = "SELECT * FROM register_students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $message = "Student not found";
    }
    
    $stmt->close();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    // Get form data
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $course = $_POST['course'];
    $department = $_POST['department'];
    $school = $_POST['school'];
    $date_of_birth = $_POST['date_of_birth'];
    $phone_number = $_POST['phone_number'];
    
    // Prepare SQL and bind parameters
    $stmt = $conn->prepare("UPDATE register_students SET first_name = ?, middle_name = ?, last_name = ?, course = ?, department = ?, school = ?, date_of_birth = ?, phone_number = ? WHERE student_id = ?");
    $stmt->bind_param("ssssssssi", $first_name, $middle_name, $last_name, $course, $department, $school, $date_of_birth, $phone_number, $student_id);
    
    // Execute query
    if ($stmt->execute()) {
        $message = "Student information updated successfully!";
        
        // Refresh student data
        $sql = "SELECT * FROM register_students WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
    } else {
        $message = "Error: " . $stmt->error;
    }
    
    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-row {
            display: flex;
            gap: 20px;
        }
        .form-col {
            flex: 1;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-cancel {
            background-color: #f44336;
        }
        .btn-cancel:hover {
            background-color: #d32f2f;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        .nav-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #4CAF50;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Student Information</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($student): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo $student['first_name']; ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="<?php echo $student['middle_name']; ?>">
                    </div>
                    <div class="form-col">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo $student['last_name']; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="course">Course</label>
                        <input type="text" id="course" name="course" value="<?php echo $student['course']; ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="department">Department</label>
                        <input type="text" id="department" name="department" value="<?php echo $student['department']; ?>" required>
                    </div>
                </div>
                
                <label for="school">School</label>
                <input type="text" id="school" name="school" value="<?php echo $student['school']; ?>" required>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $student['date_of_birth']; ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" value="<?php echo $student['phone_number']; ?>" required>
                    </div>
                </div>
                
                <div class="btn-container">
                    <div>
                        <button type="submit" class="btn">Update Student</button>
                        <a href="view_students.php" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <p class="error">Student not found or no ID provided.</p>
            <div class="nav-links">
                <a href="view_students.php">Back to Students List</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

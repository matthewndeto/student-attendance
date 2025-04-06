<?php
include 'db_connection.php';

$message = '';

// Get all students
$students_query = "SELECT student_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM register_students";
$students_result = $conn->query($students_query);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $student_id = $_POST['student_id'];
    $unit_code = $_POST['unit_code'];
    $unit_name = $_POST['unit_name'];
    $lecturer = $_POST['lecturer'];
    $date = $_POST['date'];
    $classroom = $_POST['classroom'];
    
    // Prepare SQL and bind parameters
    $stmt = $conn->prepare("INSERT INTO attendance_register (student_id, unit_code, unit_name, lecturer, date, classroom) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $student_id, $unit_code, $unit_name, $lecturer, $date, $classroom);
    
    // Execute query
    if ($stmt->execute()) {
        $message = "Attendance recorded successfully!";
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
    <title>Attendance Register</title>
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
        }
        .btn:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance Register</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="student_id">Select Student</label>
            <select id="student_id" name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php
                if ($students_result->num_rows > 0) {
                    while($student = $students_result->fetch_assoc()) {
                        echo "<option value='" . $student["student_id"] . "'>" . $student["full_name"] . "</option>";
                    }
                }
                ?>
            </select>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="unit_code">Unit Code</label>
                    <input type="text" id="unit_code" name="unit_code" required>
                </div>
                <div class="form-col">
                    <label for="unit_name">Unit Name</label>
                    <input type="text" id="unit_name" name="unit_name" required>
                </div>
            </div>
            
            <label for="lecturer">Lecturer</label>
            <input type="text" id="lecturer" name="lecturer" required>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-col">
                    <label for="classroom">Classroom</label>
                    <input type="text" id="classroom" name="classroom" required>
                </div>
            </div>
            
            <button type="submit" class="btn">Record Attendance</button>
        </form>
        
        <div class="nav-links">
            <a href="student_registration.php">Register Student</a> | 
            <a href="view_students.php">View All Students</a> | 
            <a href="view_attendance.php">View Attendance Records</a>
        </div>
    </div>
</body>
</html>

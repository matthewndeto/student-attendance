<?php
include 'db_connection.php';

$message = '';
$attendance = null;

// Get all students for dropdown
$students_query = "SELECT student_id, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM register_students";
$students_result = $conn->query($students_query);

// Check if attendance ID is provided
if (isset($_GET['id'])) {
    $attendance_id = $_GET['id'];
    
    // Get attendance information
    $sql = "SELECT * FROM attendance_register WHERE attendance_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendance_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $attendance = $result->fetch_assoc();
    } else {
        $message = "Attendance record not found";
    }
    
    $stmt->close();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attendance_id'])) {
    // Get form data
    $attendance_id = $_POST['attendance_id'];
    $student_id = $_POST['student_id'];
    $unit_code = $_POST['unit_code'];
    $unit_name = $_POST['unit_name'];
    $lecturer = $_POST['lecturer'];
    $date = $_POST['date'];
    $classroom = $_POST['classroom'];
    
    // Prepare SQL and bind parameters
    $stmt = $conn->prepare("UPDATE attendance_register SET student_id = ?, unit_code = ?, unit_name = ?, lecturer = ?, date = ?, classroom = ? WHERE attendance_id = ?");
    $stmt->bind_param("isssssi", $student_id, $unit_code, $unit_name, $lecturer, $date, $classroom, $attendance_id);
    
    // Execute query
    if ($stmt->execute()) {
        $message = "Attendance record updated successfully!";
        
        // Refresh attendance data
        $sql = "SELECT * FROM attendance_register WHERE attendance_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $attendance_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $attendance = $result->fetch_assoc();
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
    <title>Edit Attendance Record</title>
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
        <h2>Edit Attendance Record</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($attendance): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="attendance_id" value="<?php echo $attendance['attendance_id']; ?>">
                
                <label for="student_id">Select Student</label>
                <select id="student_id" name="student_id" required>
                    <?php
                    if ($students_result->num_rows > 0) {
                        while($student = $students_result->fetch_assoc()) {
                            $selected = ($student["student_id"] == $attendance["student_id"]) ? "selected" : "";
                            echo "<option value='" . $student["student_id"] . "' " . $selected . ">" . $student["full_name"] . "</option>";
                        }
                    }
                    ?>
                </select>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="unit_code">Unit Code</label>
                        <input type="text" id="unit_code" name="unit_code" value="<?php echo $attendance['unit_code']; ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="unit_name">Unit Name</label>
                        <input type="text" id="unit_name" name="unit_name" value="<?php echo $attendance['unit_name']; ?>" required>
                    </div>
                </div>
                
                <label for="lecturer">Lecturer</label>
                <input type="text" id="lecturer" name="lecturer" value="<?php echo $attendance['lecturer']; ?>" required>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" value="<?php echo $attendance['date']; ?>" required>
                    </div>
                    <div class="form-col">
                        <label for="classroom">Classroom</label>
                        <input type="text" id="classroom" name="classroom" value="<?php echo $attendance['classroom']; ?>" required>
                    </div>
                </div>
                
                <div class="btn-container">
                    <div>
                        <button type="submit" class="btn">Update Attendance</button>
                        <a href="view_attendance.php" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <p class="error">Attendance record not found or no ID provided.</p>
            <div class="nav-links">
                <a href="view_attendance.php">Back to Attendance Records</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

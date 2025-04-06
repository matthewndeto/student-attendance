<?php
include 'db_connection.php';

// Get count of students
$student_count_query = "SELECT COUNT(*) as total FROM register_students";
$student_result = $conn->query($student_count_query);
$student_count = $student_result->fetch_assoc()['total'];

// Get count of attendance records
$attendance_count_query = "SELECT COUNT(*) as total FROM attendance_register";
$attendance_result = $conn->query($attendance_count_query);
$attendance_count = $attendance_result->fetch_assoc()['total'];

// Get recent attendance records
$recent_query = "SELECT a.*, CONCAT(s.first_name, ' ', s.last_name) AS student_name 
                FROM attendance_register a 
                JOIN register_students s ON a.student_id = s.student_id
                ORDER BY a.date DESC LIMIT 5";
$recent_result = $conn->query($recent_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            flex: 1;
            min-width: 250px;
        }
        .card h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            flex: 1;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            margin: 10px 0;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
            text-align: center;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .actions .btn {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Student Attendance Management System</h1>
        </header>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Students</div>
                <div class="stat-number"><?php echo $student_count; ?></div>
                <a href="view_students.php" class="btn">View All</a>
            </div>
            <div class="stat-card">
                <div class="stat-label">Attendance Records</div>
                <div class="stat-number"><?php echo $attendance_count; ?></div>
                <a href="view_attendance.php" class="btn">View All</a>
            </div>
        </div>
        
        <div class="actions">
            <a href="student_registration.php" class="btn">Register New Student</a>
            <a href="attendance.php" class="btn">Record Attendance</a>
        </div>
        
        <div class="dashboard">
            <div class="card">
                <h3>Recent Attendance Records</h3>
                <?php if ($recent_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Unit</th>
                            <th>Date</th>
                            <th>Classroom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recent_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['unit_code'] . ' - ' . $row['unit_name']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['classroom']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No attendance records found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

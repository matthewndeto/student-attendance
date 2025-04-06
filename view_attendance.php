<?php
include 'db_connection.php';

// Query to get all attendance records with student names
$sql = "SELECT a.*, CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS student_name 
        FROM attendance_register a 
        JOIN register_students s ON a.student_id = s.student_id
        ORDER BY a.date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
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
            max-width: 1200px;
            margin: 0 auto;
            overflow-x: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .nav-links {
            text-align: center;
            margin: 20px 0;
        }
        .nav-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #4CAF50;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
            display: inline-block;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-delete {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Attendance Records</h2>
        
        <div class="nav-links">
            <a href="student_registration.php">Register Student</a> | 
            <a href="view_students.php">View Students</a> | 
            <a href="attendance.php">Record Attendance</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Unit Code</th>
                    <th>Unit Name</th>
                    <th>Lecturer</th>
                    <th>Date</th>
                    <th>Classroom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["attendance_id"] . "</td>";
                        echo "<td>" . $row["student_name"] . "</td>";
                        echo "<td>" . $row["unit_code"] . "</td>";
                        echo "<td>" . $row["unit_name"] . "</td>";
                        echo "<td>" . $row["lecturer"] . "</td>";
                        echo "<td>" . $row["date"] . "</td>";
                        echo "<td>" . $row["classroom"] . "</td>";
                        echo "<td>
                                <a href='edit_attendance.php?id=" . $row["attendance_id"] . "' class='btn btn-edit'>Edit</a>
                                <a href='delete_attendance.php?id=" . $row["attendance_id"] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                             </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align:center'>No attendance records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

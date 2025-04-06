<?php
include 'db_connection.php';

// Query to get all students
$sql = "SELECT * FROM register_students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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
        <h2>Registered Students</h2>
        
        <div class="nav-links">
            <a href="student_registration.php">Register New Student</a> | 
            <a href="attendance.php">Attendance Register</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Department</th>
                    <th>School</th>
                    <th>Date of Birth</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $fullName = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];
                        echo "<tr>";
                        echo "<td>" . $row["student_id"] . "</td>";
                        echo "<td>" . $fullName . "</td>";
                        echo "<td>" . $row["course"] . "</td>";
                        echo "<td>" . $row["department"] . "</td>";
                        echo "<td>" . $row["school"] . "</td>";
                        echo "<td>" . $row["date_of_birth"] . "</td>";
                        echo "<td>" . $row["phone_number"] . "</td>";
                        echo "<td>
                                <a href='edit_student.php?id=" . $row["student_id"] . "' class='btn btn-edit'>Edit</a>
                                <a href='delete_student.php?id=" . $row["student_id"] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                             </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align:center'>No students registered yet</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

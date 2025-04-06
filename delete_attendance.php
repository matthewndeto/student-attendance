<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $attendance_id = $_GET['id'];
    
    // Check if attendance record exists
    $check_sql = "SELECT * FROM attendance_register WHERE attendance_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $attendance_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Delete the attendance record
        $delete_sql = "DELETE FROM attendance_register WHERE attendance_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $attendance_id);
        
        if ($delete_stmt->execute()) {
            header("Location: view_attendance.php?deleted=success");
            exit();
        } else {
            header("Location: view_attendance.php?deleted=error");
            exit();
        }
        
        $delete_stmt->close();
    } else {
        header("Location: view_attendance.php?deleted=notfound");
        exit();
    }
    
    $check_stmt->close();
} else {
    header("Location: view_attendance.php");
    exit();
}
?>

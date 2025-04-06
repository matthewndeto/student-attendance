<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    
    // First check if student exists
    $check_sql = "SELECT * FROM register_students WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $student_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Check if student has attendance records
        $attendance_check = "SELECT COUNT(*) as count FROM attendance_register WHERE student_id = ?";
        $att_stmt = $conn->prepare($attendance_check);
        $att_stmt->bind_param("i", $student_id);
        $att_stmt->execute();
        $att_result = $att_stmt->get_result();
        $att_count = $att_result->fetch_assoc()['count'];
        
        if ($att_count > 0) {
            // Delete attendance records first
            $delete_att = "DELETE FROM attendance_register WHERE student_id = ?";
            $att_del_stmt = $conn->prepare($delete_att);
            $att_del_stmt->bind_param("i", $student_id);
            $att_del_stmt->execute();
            $att_del_stmt->close();
        }
        
        // Now delete the student
        $delete_sql = "DELETE FROM register_students WHERE student_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $student_id);
        
        if ($delete_stmt->execute()) {
            header("Location: view_students.php?deleted=success");
            exit();
        } else {
            header("Location: view_students.php?deleted=error");
            exit();
        }
        
        $delete_stmt->close();
    } else {
        header("Location: view_students.php?deleted=notfound");
        exit();
    }
    
    $check_stmt->close();
} else {
    header("Location: view_students.php");
    exit();
}
?>

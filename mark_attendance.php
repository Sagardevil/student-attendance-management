<?php
require_once 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $attendance_date = trim($_POST['attendance_date'] ?? '');
    $status = trim($_POST['status'] ?? '');

    // Validate input
    if (empty($student_id) || empty($attendance_date) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    // Validate status
    if (!in_array($status, ['Present', 'Absent'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid attendance status']);
        exit;
    }

    // Validate date format
    $date = DateTime::createFromFormat('Y-m-d', $attendance_date);
    if (!$date || $date->format('Y-m-d') !== $attendance_date) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format']);
        exit;
    }

    try {
        // Check if student exists
        $checkStudent = $pdo->prepare("SELECT student_id FROM students WHERE student_id = ?");
        $checkStudent->execute([$student_id]);

        if (!$checkStudent->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
            exit;
        }

        // Check if attendance already marked for this date
        $checkAttendance = $pdo->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = ?");
        $checkAttendance->execute([$student_id, $attendance_date]);

        if ($checkAttendance->fetch()) {
            // Update existing attendance
            $stmt = $pdo->prepare("UPDATE attendance SET status = ? WHERE student_id = ? AND date = ?");
            $result = $stmt->execute([$status, $student_id, $attendance_date]);
            $message = 'Attendance updated successfully';
        } else {
            // Insert new attendance record
            $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
            $result = $stmt->execute([$student_id, $attendance_date, $status]);
            $message = 'Attendance marked successfully';
        }

        if ($result) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to mark attendance']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
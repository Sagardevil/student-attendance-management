<?php
require_once 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $student_name = trim($_POST['student_name'] ?? '');
    $class_name = trim($_POST['class_name'] ?? '');

    // Validate input
    if (empty($student_id) || empty($student_name) || empty($class_name)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    // Validate student ID format (alphanumeric)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $student_id)) {
        echo json_encode(['success' => false, 'message' => 'Student ID should only contain letters and numbers']);
        exit;
    }

    try {
        // Check if student ID already exists
        $checkStmt = $pdo->prepare("SELECT student_id FROM students WHERE student_id = ?");
        $checkStmt->execute([$student_id]);

        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Student ID already exists']);
            exit;
        }

        // Insert new student
        $stmt = $pdo->prepare("INSERT INTO students (student_id, name, class) VALUES (?, ?, ?)");
        $result = $stmt->execute([$student_id, $student_name, $class_name]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Student added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add student']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
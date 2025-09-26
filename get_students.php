<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT student_id, name, class, created_at FROM students ORDER BY created_at DESC");
    $stmt->execute();
    $students = $stmt->fetchAll();

    echo json_encode($students);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
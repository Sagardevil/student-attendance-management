<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT a.student_id, s.name, s.class, a.date, a.status, a.created_at 
            FROM attendance a 
            JOIN students s ON a.student_id = s.student_id";

    $params = [];
    $conditions = [];

    // Add filters if provided
    if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
        $conditions[] = "a.student_id = ?";
        $params[] = $_GET['student_id'];
    }

    if (isset($_GET['date']) && !empty($_GET['date'])) {
        $conditions[] = "a.date = ?";
        $params[] = $_GET['date'];
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY a.date DESC, s.name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $attendance = $stmt->fetchAll();

    echo json_encode($attendance);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
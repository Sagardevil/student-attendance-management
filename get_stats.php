<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $today = date('Y-m-d');

    // Get total number of students
    $totalStudentsStmt = $pdo->prepare("SELECT COUNT(*) as total FROM students");
    $totalStudentsStmt->execute();
    $totalStudents = $totalStudentsStmt->fetch()['total'];

    // Get present count for today
    $presentTodayStmt = $pdo->prepare("SELECT COUNT(*) as present FROM attendance WHERE date = ? AND status = 'Present'");
    $presentTodayStmt->execute([$today]);
    $presentToday = $presentTodayStmt->fetch()['present'];

    // Get absent count for today
    $absentTodayStmt = $pdo->prepare("SELECT COUNT(*) as absent FROM attendance WHERE date = ? AND status = 'Absent'");
    $absentTodayStmt->execute([$today]);
    $absentToday = $absentTodayStmt->fetch()['absent'];

    // Calculate attendance rate for today
    $totalMarkedToday = $presentToday + $absentToday;
    $attendanceRate = 0;
    if ($totalMarkedToday > 0) {
        $attendanceRate = round(($presentToday / $totalMarkedToday) * 100, 1);
    }

    $stats = [
        'total_students' => $totalStudents,
        'present_today' => $presentToday,
        'absent_today' => $absentToday,
        'attendance_rate' => $attendanceRate
    ];

    echo json_encode($stats);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
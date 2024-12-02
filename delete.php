<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die("No student ID provided.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT image FROM student WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if ($student) {
    if ($student['image'] && file_exists($student['image'])) {
        unlink($student['image']);
    }

    $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Student deleted successfully!";
    } else {
        echo "Failed to delete student.";
    }
    $stmt->close();
} else {
    echo "Student not found.";
}

$conn->close();
?>

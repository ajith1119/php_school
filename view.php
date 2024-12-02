<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die("No student ID provided.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("
    SELECT student.*, classes.name AS class_name 
    FROM student 
    LEFT JOIN classes ON student.class_id = classes.class_id 
    WHERE student.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Student</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h1>Student Details</h1>
        <div class="card">
            <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($student['address'])) ?></p>
            <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name']) ?></p>
            <p><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
            <?php if (!empty($student['image'])): ?>
                <div>
                    <strong>Image:</strong><br>
                    <img src="<?= htmlspecialchars($student['image']) ?>" alt="Student Image" class="thumbnail">
                </div>
            <?php endif; ?>
        </div>

        <a href="index.php" class="btn">Back to Home</a>
        <a href="edit.php?id=<?= $student['id'] ?>" class="btn">Edit</a>
        <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
    </div>

  
</body>
</html>

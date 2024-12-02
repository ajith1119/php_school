<?php
require 'db.php';

$query = "
    SELECT student.id, student.name, student.email, student.image, student.created_at, 
           classes.name AS class_name
    FROM student
    LEFT JOIN classes ON student.class_id = classes.class_id
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Students</title>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Students</h1>
    <a href="create.php" class="btn btn-primary mb-3">Add Student</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Class</th>
                <th>Created At</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['class_name'] ?? 'N/A' ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><img src="uploads/<?= $row['image'] ?>" width="50"></td>
                    <td>
                        <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

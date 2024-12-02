<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $className = $_POST['name'];
    if (!empty($className)) {
        $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->bind_param("s", $className);
        $stmt->execute();
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM classes");
$classes = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="container">
        <h1>Manage Classes</h1>

        <form method="POST" class="class-form">
            <input type="text" name="name" required class="form-input" placeholder="Enter class name" />
            <button type="submit" class="btn-submit">Add Class</button>
            <a href="index.php" class="btn btn-info">Index Page</a>
        </form>

        <ul class="class-list">
            <?php foreach ($classes as $class): ?>
                <li class="class-item"><?= htmlspecialchars($class['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

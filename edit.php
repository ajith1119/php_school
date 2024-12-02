<?php
require 'db.php';

$errors = [];
$success = null;
$student = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("Student not found.");
    }
} else {
    die("No student ID provided.");
}

$classResult = $conn->query("SELECT class_id, name FROM classes");
$classes = $classResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $_FILES['image'];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    $imagePath = $student['image'];
    if ($image && $image['error'] === 0) {
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowedExtensions)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $imagePath = $uploadDir . uniqid() . '.' . $ext;
            move_uploaded_file($image['tmp_name'], $imagePath);
        } else {
            $errors[] = "Invalid image format.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssisi", $name, $email, $address, $class_id, $imagePath, $id);

        if ($stmt->execute()) {
            $success = "Student updated successfully!";
            $student['name'] = $name;
            $student['email'] = $email;
            $student['address'] = $address;
            $student['class_id'] = $class_id;
            $student['image'] = $imagePath;
        } else {
            $errors[] = "Failed to update student.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <?php if ($errors): ?>
        <div><?php echo implode('<br>', $errors); ?></div>
    <?php elseif ($success): ?>
        <div><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required />
        <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required />
        <textarea name="address" required><?= htmlspecialchars($student['address']) ?></textarea>
        <select name="class_id" required>
            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['class_id'] ?>" <?= $class['class_id'] == $student['class_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($class['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="file" name="image" />
        <?php if ($student['image']): ?>
            <img src="<?= $student['image'] ?>" alt="Current Image" width="100">
        <?php endif; ?>
        <button type="submit">Update Student</button>
    </form>
</body>
</html>

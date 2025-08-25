<?php

declare(strict_types=1);

require_once 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    exit('User not found.');
}

$name = $user['name'];
$email = $user['email'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || $email === '') {
        $error = 'All fields are required.';
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
            $stmt->execute([$name, $email, $id]);
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <p>
            <label>Name:<br>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
            </label>
        </p>
        <p>
            <label>Email:<br>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
            </label>
        </p>
        <p>
            <button type="submit">Update</button>
            <a href="index.php">Cancel</a>
        </p>
    </form>
</body>
</html>

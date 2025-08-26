<?php

declare(strict_types=1);

require_once 'db.php';

$stmt = $pdo->query('SELECT * FROM users ORDER BY id DESC');
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
</head>
<body>
    <h1>Users</h1>

    
    <p><a href="create.php">Add New User</a></p>
    <table border="1" cellpadding="10">
        <thead> 
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Created</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <a href="update.php?id=<?= $user['id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Delete user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <hr>
    <!-- Aggregator Link -->
    <p><a href="../unifiedTravelData.html">View Aggregated Travel Data</a></p>

</body>
</html>

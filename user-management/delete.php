<?php

declare(strict_types=1);

require_once 'db.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;

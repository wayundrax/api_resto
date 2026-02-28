<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_resto', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek struktur tabel transaksi
    $stmt = $pdo->query("SHOW CREATE TABLE transaksi");
    $transaksi_structure = $stmt->fetch();

    // Cek data user
    $stmt = $pdo->query("SELECT * FROM user");
    $users = $stmt->fetchAll();

    // Cek struktur tabel user
    $stmt = $pdo->query("SHOW CREATE TABLE user");
    $user_structure = $stmt->fetch();

    echo json_encode([
        'users' => $users,
        'user_count' => count($users),
        'transaksi_structure' => $transaksi_structure,
        'user_structure' => $user_structure
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
}

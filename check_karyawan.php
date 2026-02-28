<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_resto', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek struktur tabel karyawan
    $stmt = $pdo->query("SHOW CREATE TABLE karyawan");
    $karyawan_structure = $stmt->fetch();

    // Cek kolom di tabel karyawan
    $stmt = $pdo->query("DESCRIBE karyawan");
    $karyawan_columns = $stmt->fetchAll();

    echo json_encode([
        'karyawan_columns' => $karyawan_columns,
        'karyawan_structure' => $karyawan_structure
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
}

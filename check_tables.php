<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_resto', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek tabel yang ada
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $result = ['tables' => $tables, 'data' => []];

    // Cek data di tabel menu jika ada
    if (in_array('menu', $tables)) {
        $stmt = $pdo->query('SELECT * FROM menu');
        $result['data']['menu'] = $stmt->fetchAll();
    }

    // Cek data di tabel kategori jika ada
    if (in_array('kategori', $tables)) {
        $stmt = $pdo->query('SELECT * FROM kategori');
        $result['data']['kategori'] = $stmt->fetchAll();
    }

    // Cek data di tabel meja jika ada
    if (in_array('meja', $tables)) {
        $stmt = $pdo->query('SELECT * FROM meja');
        $result['data']['meja'] = $stmt->fetchAll();
    }

    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
}

<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Test database connection
$host = 'localhost';
$db   = 'db_resto';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test query kategori
    $stmt_kategori = $pdo->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
    $kategori = $stmt_kategori->fetchAll();

    // Test query menu dengan join kategori
    $stmt_menu = $pdo->query("
        SELECT m.id, m.nama_menu, m.harga, m.stok, m.status, 
               m.deskripsi, k.nama_kategori
        FROM menu m
        JOIN kategori k ON m.id_kategori = k.id
        WHERE m.status = 'tersedia'
        ORDER BY k.nama_kategori ASC, m.nama_menu ASC
    ");
    $menu = $stmt_menu->fetchAll();

    // Test query meja
    $stmt_meja = $pdo->query("SELECT id, no_meja, status, kapasitas FROM meja WHERE status = 'available' ORDER BY no_meja ASC");
    $meja = $stmt_meja->fetchAll();

    echo json_encode([
        'status' => 'success',
        'message' => 'Database connected!',
        'data' => [
            'kategori' => $kategori,
            'menu' => $menu,
            'meja' => $meja
        ],
        'count' => [
            'kategori' => count($kategori),
            'menu' => count($menu),
            'meja' => count($meja)
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}

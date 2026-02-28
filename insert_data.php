<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_resto', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert Kategori
    $pdo->exec("INSERT INTO kategori (nama_kategori) VALUES 
        ('Makanan'),
        ('Minuman'),
        ('Snack'),
        ('Dessert')
    ");

    // Insert Menu Makanan
    $pdo->exec("INSERT INTO menu (nama_menu, harga, stok, status, id_kategori, deskripsi) VALUES 
        -- Makanan (id_kategori = 1)
        ('Nasi Goreng Spesial', 25000, 50, 'tersedia', 1, 'Nasi goreng dengan telur, ayam, dan sayuran'),
        ('Mie Goreng', 20000, 50, 'tersedia', 1, 'Mie goreng dengan sayuran dan telur'),
        ('Ayam Goreng Kremes', 30000, 30, 'tersedia', 1, 'Ayam goreng dengan kremesan renyah'),
        ('Soto Ayam', 22000, 40, 'tersedia', 1, 'Soto ayam kuah kuning dengan nasi'),
        ('Gado-Gado', 18000, 35, 'tersedia', 1, 'Sayuran dengan bumbu kacang'),
        ('Sate Ayam', 28000, 25, 'tersedia', 1, 'Sate ayam 10 tusuk dengan bumbu kacang'),
        ('Nasi Uduk', 15000, 45, 'tersedia', 1, 'Nasi uduk dengan lauk pauk'),
        ('Rendang', 35000, 20, 'tersedia', 1, 'Rendang daging sapi pedas'),
        
        -- Minuman (id_kategori = 2)
        ('Es Teh Manis', 5000, 100, 'tersedia', 2, 'Teh manis dingin'),
        ('Es Jeruk', 7000, 80, 'tersedia', 2, 'Jeruk peras dengan es'),
        ('Jus Alpukat', 15000, 30, 'tersedia', 2, 'Jus alpukat segar'),
        ('Jus Mangga', 15000, 30, 'tersedia', 2, 'Jus mangga segar'),
        ('Kopi Hitam', 8000, 50, 'tersedia', 2, 'Kopi hitam panas'),
        ('Cappuccino', 18000, 40, 'tersedia', 2, 'Kopi cappuccino'),
        ('Teh Tarik', 10000, 60, 'tersedia', 2, 'Teh tarik panas'),
        ('Air Mineral', 3000, 200, 'tersedia', 2, 'Air mineral botol'),
        
        -- Snack (id_kategori = 3)
        ('Pisang Goreng', 10000, 50, 'tersedia', 3, 'Pisang goreng crispy'),
        ('Tahu Isi', 12000, 40, 'tersedia', 3, 'Tahu isi sayuran'),
        ('Cireng', 8000, 60, 'tersedia', 3, 'Cireng isi pedas'),
        ('Kentang Goreng', 15000, 45, 'tersedia', 3, 'Kentang goreng crispy'),
        
        -- Dessert (id_kategori = 4)
        ('Es Campur', 12000, 35, 'tersedia', 4, 'Es campur dengan buah dan agar-agar'),
        ('Es Krim Vanila', 10000, 40, 'tersedia', 4, 'Es krim vanila 2 scoop'),
        ('Puding Coklat', 8000, 30, 'tersedia', 4, 'Puding coklat lembut'),
        ('Pancake', 20000, 25, 'tersedia', 4, 'Pancake dengan madu dan buah')
    ");

    // Insert data meja
    $pdo->exec("INSERT INTO meja (no_meja, status, kapasitas) VALUES 
        (1, 'available', 4),
        (2, 'available', 4),
        (3, 'available', 2),
        (4, 'available', 2),
        (5, 'available', 6),
        (6, 'available', 4),
        (7, 'available', 4),
        (8, 'available', 8)
    ");

    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil dimasukkan!',
        'kategori_count' => $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn(),
        'menu_count' => $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn(),
        'meja_count' => $pdo->query("SELECT COUNT(*) FROM meja")->fetchColumn()
    ], JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}

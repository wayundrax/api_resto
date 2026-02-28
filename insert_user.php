<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=db_resto', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek apakah sudah ada data karyawan
    $stmt = $pdo->query("SELECT COUNT(*) FROM karyawan");
    $karyawan_count = $stmt->fetchColumn();

    if ($karyawan_count == 0) {
        // Insert karyawan terlebih dahulu
        $pdo->exec("INSERT INTO karyawan (nama_karyawan, no_hp, jabatan) VALUES 
            ('Admin Resto', '081234567890', 'Manager'),
            ('Kasir Satu', '081234567891', 'Kasir'),
            ('Kasir Dua', '081234567892', 'Kasir')
        ");
    }

    // Cek apakah sudah ada user
    $stmt = $pdo->query("SELECT COUNT(*) FROM user");
    $user_count = $stmt->fetchColumn();

    if ($user_count == 0) {
        // Insert user (password: admin123, kasir123)
        $pdo->exec("INSERT INTO user (username, email, password, role, status, id_karyawan) VALUES 
            ('admin', 'admin@resto.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'owner', 'aktif', 1),
            ('kasir1', 'kasir1@resto.com', '" . password_hash('kasir123', PASSWORD_DEFAULT) . "', 'kasir', 'aktif', 2),
            ('kasir2', 'kasir2@resto.com', '" . password_hash('kasir123', PASSWORD_DEFAULT) . "', 'kasir', 'aktif', 3)
        ");
    }

    // Ambil data yang sudah diinsert
    $stmt = $pdo->query("SELECT u.id, u.username, u.email, u.role, u.status, k.nama_karyawan 
                         FROM user u 
                         LEFT JOIN karyawan k ON u.id_karyawan = k.id");
    $users = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'message' => 'Data user dan karyawan berhasil ditambahkan',
        'users' => $users,
        'info' => [
            'note' => 'Gunakan id_user dari salah satu user di atas untuk transaksi',
            'default_user_id' => 2,
            'default_username' => 'kasir1',
            'passwords' => [
                'admin' => 'admin123',
                'kasir' => 'kasir123'
            ]
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}

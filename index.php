<?php
// ============================================================
// KONFIGURASI AWAL
// ============================================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request (untuk CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ============================================================
// KONEKSI DATABASE
// ============================================================
$host = 'localhost';
$db   = 'db_resto';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . $e->getMessage()]);
    exit();
}

// ============================================================
// ROUTING
// ============================================================
$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hapus prefix folder jika ada, misal /api_resto/kategori → /kategori
$basePath = '/API_RESTO'; // Sesuaikan dengan nama folder Anda
$uri = str_replace($basePath, '', $uri);
$uri = trim($uri, '/');
$parts = explode('/', $uri); // ['kategori'] atau ['meja', 'available'] dll

$resource = $parts[0] ?? '';
$param    = $parts[1] ?? null; // misal id atau 'available'

// ============================================================
// HELPER FUNCTION
// ============================================================
function respond($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

// ============================================================
// ROUTE: /kategori
// ============================================================
if ($resource === 'kategori') {

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
        respond(['status' => 'success', 'data' => $stmt->fetchAll()]);
    }

    respond(['status' => 'error', 'message' => 'Method tidak diizinkan'], 405);
}

// ============================================================
// ROUTE: /menu
// ============================================================
if ($resource === 'menu') {

    if ($method === 'GET') {
        $idKategori = $_GET['id_kategori'] ?? null;

        if ($idKategori) {
            $stmt = $pdo->prepare("
                SELECT m.id, m.nama_menu, m.harga, m.stok, m.status,
                       m.foto, m.deskripsi, m.id_kategori, k.nama_kategori
                FROM menu m
                JOIN kategori k ON m.id_kategori = k.id
                WHERE m.id_kategori = ? AND m.status = 'tersedia'
                ORDER BY m.nama_menu ASC
            ");
            $stmt->execute([$idKategori]);
        } else {
            $stmt = $pdo->prepare("
                SELECT m.id, m.nama_menu, m.harga, m.stok, m.status,
                       m.foto, m.deskripsi, m.id_kategori, k.nama_kategori
                FROM menu m
                JOIN kategori k ON m.id_kategori = k.id
                WHERE m.status = 'tersedia'
                ORDER BY m.nama_menu ASC
            ");
            $stmt->execute();
        }

        respond(['status' => 'success', 'data' => $stmt->fetchAll()]);
    }

    respond(['status' => 'error', 'message' => 'Method tidak diizinkan'], 405);
}

// ============================================================
// ROUTE: /meja  dan  /meja/available
// ============================================================
if ($resource === 'meja') {

    if ($method === 'GET') {

        // GET /meja/available → hanya yang available
        if ($param === 'available') {
            $stmt = $pdo->prepare("
                SELECT id, no_meja, status, kapasitas
                FROM meja
                WHERE status = 'available'
                ORDER BY no_meja ASC
            ");
            $stmt->execute();
            respond(['status' => 'success', 'data' => $stmt->fetchAll()]);
        }

        // GET /meja → semua meja
        $stmt = $pdo->query("SELECT id, no_meja, status, kapasitas FROM meja ORDER BY no_meja ASC");
        respond(['status' => 'success', 'data' => $stmt->fetchAll()]);
    }

    respond(['status' => 'error', 'message' => 'Method tidak diizinkan'], 405);
}

// ============================================================
// ROUTE: /transaksi
// ============================================================
if ($resource === 'transaksi') {

    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);

        // Validasi field wajib (id_meja dihapus karena optional untuk Take Away)
        $required = ['nama_kostumer', 'total_bayar', 'metode_pembayaran'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                respond(['status' => 'error', 'message' => "Field '$field' wajib diisi"], 422);
            }
        }

        // Ambil id_meja, set NULL jika tidak ada (untuk Take Away)
        $idMeja = isset($body['id_meja']) && !empty($body['id_meja']) ? $body['id_meja'] : null;

        try {
            $pdo->beginTransaction();

            // Insert transaksi
            // $stmt = $pdo->prepare("
            //     INSERT INTO transaksi
            //         (nama_kostumer, total_bayar, tanggal_transaksi, metode_pembayaran, status, id_meja)
            //     VALUES
            //         (:nama_kostumer, :total_bayar, NOW(), :metode_pembayaran, 'belum dibayar', :id_meja)
            // ");

            // Ganti query INSERT transaksi yang lama dengan ini:
            $stmt = $pdo->prepare("
            INSERT INTO transaksi
            (nama_kostumer, total_bayar, tanggal_transaksi, metode_pembayaran, status, id_meja, id_user)
            VALUES
            (:nama_kostumer, :total_bayar, NOW(), :metode_pembayaran, :status, :id_meja, :id_user)
            ");

            $stmt->execute([
                ':nama_kostumer'     => $body['nama_kostumer'],
                ':total_bayar'       => $body['total_bayar'],
                ':metode_pembayaran' => $body['metode_pembayaran'],
                ':status'            => $body['status'] ?? 'belum dibayar',
                ':id_meja'           => $idMeja,  // Bisa NULL untuk Take Away
                ':id_user'           => $body['id_user'] ?? 2,  // Default ke kasir1 (id=2)
            ]);

            $idTransaksi = $pdo->lastInsertId();

            // Update status meja hanya jika ada id_meja (Dine In)
            if ($idMeja !== null) {
                $stmtMeja = $pdo->prepare("UPDATE meja SET status = 'booking' WHERE id = ?");
                $stmtMeja->execute([$idMeja]);
            }

            $pdo->commit();

            respond([
                'status'       => 'success',
                'message'      => 'Transaksi berhasil dibuat',
                'id_transaksi' => (int) $idTransaksi
            ], 201);
        } catch (Exception $e) {
            $pdo->rollBack();
            respond(['status' => 'error', 'message' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    // PUT /transaksi/{id} → update status transaksi (untuk QRIS)
    if ($method === 'PUT' && $param !== null) {
        $body = json_decode(file_get_contents('php://input'), true);

        if (empty($body['status'])) {
            respond(['status' => 'error', 'message' => "Field 'status' wajib diisi"], 422);
        }

        try {
            $stmt = $pdo->prepare("UPDATE transaksi SET status = :status WHERE id = :id");
            $stmt->execute([
                ':status' => $body['status'],
                ':id'     => $param
            ]);

            respond([
                'status'  => 'success',
                'message' => 'Status transaksi berhasil diupdate'
            ]);
        } catch (Exception $e) {
            respond(['status' => 'error', 'message' => 'Gagal update status: ' . $e->getMessage()], 500);
        }
    }

    respond(['status' => 'error', 'message' => 'Method tidak diizinkan'], 405);
}

// ============================================================
// ROUTE: /detail-transaksi  dan  /detail-transaksi/{id}
// ============================================================
if ($resource === 'detail-transaksi') {

    // GET /detail-transaksi/{id_transaksi} → status pesanan
    if ($method === 'GET' && $param !== null) {
        $stmt = $pdo->prepare("
            SELECT dt.id, dt.jumlah, dt.catatan, dt.status,
                   dt.metode_pembelian, dt.sub_total,
                   dt.id_transaksi, dt.id_menu,
                   m.nama_menu, m.foto
            FROM detail_transaksi dt
            JOIN menu m ON dt.id_menu = m.id
            WHERE dt.id_transaksi = ?
            ORDER BY dt.id ASC
        ");
        $stmt->execute([$param]);
        respond(['status' => 'success', 'data' => $stmt->fetchAll()]);
    }

    // POST /detail-transaksi → tambah item pesanan
    if ($method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);

        // Validasi field wajib
        $required = ['jumlah', 'metode_pembelian', 'sub_total', 'id_transaksi', 'id_menu'];
        foreach ($required as $field) {
            if (!isset($body[$field])) {
                respond(['status' => 'error', 'message' => "Field '$field' wajib diisi"], 422);
            }
        }

        try {
            $pdo->beginTransaction();

            // Insert detail transaksi
            $stmt = $pdo->prepare("
                INSERT INTO detail_transaksi
                    (jumlah, catatan, status, metode_pembelian, sub_total, id_transaksi, id_menu)
                VALUES
                    (:jumlah, :catatan, 'sedang di masak', :metode_pembelian, :sub_total, :id_transaksi, :id_menu)
            ");
            $stmt->execute([
                ':jumlah'           => $body['jumlah'],
                ':catatan'          => $body['catatan'] ?? '',
                ':metode_pembelian' => $body['metode_pembelian'],
                ':sub_total'        => $body['sub_total'],
                ':id_transaksi'     => $body['id_transaksi'],
                ':id_menu'          => $body['id_menu'],
            ]);

            // Kurangi stok menu
            $stmtStok = $pdo->prepare("UPDATE menu SET stok = stok - ? WHERE id = ?");
            $stmtStok->execute([$body['jumlah'], $body['id_menu']]);

            // Cek jika stok habis, update status menu
            $stmtCek = $pdo->prepare("SELECT stok FROM menu WHERE id = ?");
            $stmtCek->execute([$body['id_menu']]);
            $menu = $stmtCek->fetch();
            if ($menu['stok'] <= 0) {
                $stmtStatus = $pdo->prepare("UPDATE menu SET status = 'habis' WHERE id = ?");
                $stmtStatus->execute([$body['id_menu']]);
            }

            $pdo->commit();

            respond([
                'status'  => 'success',
                'message' => 'Detail transaksi berhasil ditambahkan',
                'id'      => (int) $pdo->lastInsertId()
            ], 201);
        } catch (Exception $e) {
            $pdo->rollBack();
            respond(['status' => 'error', 'message' => 'Gagal menyimpan detail: ' . $e->getMessage()], 500);
        }
    }

    respond(['status' => 'error', 'message' => 'Method tidak diizinkan'], 405);
}

// ============================================================
// ROUTE TIDAK DITEMUKAN
// ============================================================
respond(['status' => 'error', 'message' => 'Endpoint tidak ditemukan'], 404);

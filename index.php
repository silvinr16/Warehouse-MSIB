<?php session_start();
include_once 'Database.php';
include_once 'Gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);
$stmt = $gudang->read();

// Fitur Search: Filter pencarian berdasarkan nama atau lokasi gudang
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM gudang WHERE name LIKE :search OR location LIKE :search LIMIT :start, :limit";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
} else {
    $query = "SELECT * FROM gudang LIMIT :start, :limit";
    $stmt = $db->prepare($query);
}

// Pagination setup
$limit = 10; // jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total FROM gudang";
if ($search !== '') {
    $totalQuery .= " WHERE name LIKE :search OR location LIKE :search";
}
$totalStmt = $db->prepare($totalQuery);
if ($search !== '') {
    $totalStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
$totalStmt->execute();
$totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warehouse MSIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling Header */
        .header-background {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Tambahkan margin di bawah header */
        .container {
            margin-top: 20px;
        }

        /* Styling tombol create dan search */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Tabel yang responsive */
        .table-responsive {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header dengan background dan judul besar -->
    <div class="header-background">
        <h1>Warehouse MSIB</h1>
    </div>

    <div class="container">
        <!-- Notifikasi Pesan -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
            ?>
        <?php endif; ?>
<body>
    <div class="container">
        <h1 class="mt-5">Daftar Gudang</h1>
        
        <!-- Search dan Create Form -->
        <div class="action-bar">
            <form action="index.php" method="GET" class="d-flex w-100">
                <input type="text" name="search" class="form-control me-2  w-75" placeholder="Cari nama dan lokasi gedung" value="<?= $search ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
            <a href="create.php" class="btn btn-success w-25">Tambah Gudang</a>
        </div>

        <!-- Tabel Data Gudang -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Waktu Buka</th>
                        <th>Waktu Tutup</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        foreach ($result as $index => $g) {
                            echo "<tr>
                                    <td>" . ($start + $index + 1) . "</td>
                                    <td>{$g['name']}</td>
                                    <td>{$g['location']}</td>
                                    <td>{$g['capacity']}</td>
                                    <td>{$g['status']}</td>
                                    <td>{$g['opening_hour']}</td>
                                    <td>{$g['closing_hour']}</td>
                                    <td>
                                        <a href='update.php?id={$g['id']}' class='btn btn-primary btn-sm'>Edit</a>
                                        <a href='delete.php?id={$g['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Apakah Anda yakin ingin menghapus?')\">Hapus</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada gudang yang tersedia.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="index.php?page=<?= $page - 1 ?>&search=<?= $search ?>">Previous</a></li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="index.php?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="index.php?page=<?= $page + 1 ?>&search=<?= $search ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
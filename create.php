<?php
include_once 'Database.php';
include_once 'Gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);

if ($_POST) {
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];

    if ($gudang->create()) {
        session_start();
        $_SESSION['message'] = "Gudang berhasil ditambahkan!";
        $_SESSION['msg_type'] = "success";
        header("Location: index.php");
        exit;
    } else {
        session_start();
        $_SESSION['message'] = "Gagal menambahkan gudang.";
        $_SESSION['msg_type'] = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Tambah Gudang Baru</h1>
        <form action="create.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Gudang</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi Gudang</label>
                <input type="text" class="form-control" name="location" required>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Kapasitas</label>
                <input type="number" class="form-control" name="capacity" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak_aktif">Tidak Aktif</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="opening_hour" class="form-label">Jam Buka</label>
                <input type="time" class="form-control" name="opening_hour" required>
            </div>
            <div class="mb-3">
                <label for="closing_hour" class="form-label">Jam Tutup</label>
                <input type="time" class="form-control" name="closing_hour" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

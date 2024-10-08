<?php
include_once 'Database.php';
include_once 'Gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);

// Pastikan ID gudang dikirim via URL
if (isset($_GET['id'])) {
    $gudang->id = $_GET['id'];

    // Ambil data gudang berdasarkan ID
    $query = "SELECT * FROM gudang WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $gudang->id);
    $stmt->execute();

    // Ambil hasilnya
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Set property dari objek Gudang dengan data yang ada di database
        $gudang->name = $row['name'];
        $gudang->location = $row['location'];
        $gudang->capacity = $row['capacity'];
        $gudang->status = $row['status'];
        $gudang->opening_hour = $row['opening_hour'];
        $gudang->closing_hour = $row['closing_hour'];
    } else {
        echo "<div class='alert alert-danger'>Gudang tidak ditemukan.</div>";
        exit;
    }
}

if ($_POST) {
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];

    if ($gudang->update()) {
        session_start();
        $_SESSION['message'] = "Gudang berhasil diupdate!";
        $_SESSION['msg_type'] = "success";
        header("Location: index.php");
        exit;
    } else {
        session_start();
        $_SESSION['message'] = "Gagal mengupdate gudang.";
        $_SESSION['msg_type'] = "danger";
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Update Gudang</h1>
        <form action="update.php?id=<?= $gudang->id ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Gudang</label>
                <input type="text" class="form-control" name="name" value="<?= $gudang->name ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi Gudang</label>
                <input type="text" class="form-control" name="location" value="<?= $gudang->location ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Kapasitas</label>
                <input type="number" class="form-control" name="capacity" value="<?= $gudang->capacity ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" name="status">
                    <option value="aktif" <?= $gudang->status == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="tidak_aktif" <?= $gudang->status == 'tidak_aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="opening_hour" class="form-label">Jam Buka</label>
                <input type="time" class="form-control" name="opening_hour" value="<?= $gudang->opening_hour ?>" required>
            </div>
            <div class="mb-3">
                <label for="closing_hour" class="form-label">Jam Tutup</label>
                <input type="time" class="form-control" name="closing_hour" value="<?= $gudang->closing_hour ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Include Database & Gudang class
include_once 'Database.php';
include_once 'Gudang.php';

// Database connection
$database = new Database();
$db = $database->getConnection();

// Gudang object
$gudang = new Gudang($db);

// Get ID from URL
$gudang->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

// If form is submitted
if ($_POST) {
    // Set properties
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];

    // Update record
    if ($gudang->update()) {
        echo "<div class='alert alert-success'>Data berhasil diupdate.</div>";
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan, coba lagi.</div>";
    }
} else {
    // Read record based on ID
    $stmt = $gudang->readOne();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set properties
    $gudang->name = $row['name'];
    $gudang->location = $row['location'];
    $gudang->capacity = $row['capacity'];
    $gudang->status = $row['status'];
    $gudang->opening_hour = $row['opening_hour'];
    $gudang->closing_hour = $row['closing_hour'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Gudang</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="text-center mt-4">Edit Gudang</h2>

    <form action="edit.php?id=<?php echo $gudang->id; ?>" method="post">
        <div class="form-group">
            <label>Nama Gudang</label>
            <input type="text" name="name" class="form-control" value="<?php echo $gudang->name; ?>" required>
        </div>
        <div class="form-group">
            <label>Lokasi</label>
            <input type="text" name="location" class="form-control" value="<?php echo $gudang->location; ?>" required>
        </div>
        <div class="form-group">
            <label>Kapasitas</label>
            <input type="number" name="capacity" class="form-control" value="<?php echo $gudang->capacity; ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="aktif" <?php echo $gudang->status == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                <option value="tidak_aktif" <?php echo $gudang->status == 'tidak_aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>
        </div>
        <div class="form-group">
            <label>Waktu Buka</label>
            <input type="time" name="opening_hour" class="form-control" value="<?php echo $gudang->opening_hour; ?>">
        </div>
        <div class="form-group">
            <label>Waktu Tutup</label>
            <input type="time" name="closing_hour" class="form-control" value="<?php echo $gudang->closing_hour; ?>">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

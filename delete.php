<?php
include_once 'Database.php';
include_once 'Gudang.php';

$database = new Database();
$db = $database->getConnection();
$gudang = new Gudang($db);

if (isset($_GET['id'])) {
    $gudang->id = $_GET['id'];

    if ($gudang->delete()) {
        session_start();
        $_SESSION['message'] = "Gudang berhasil dihapus!";
        $_SESSION['msg_type'] = "success";
    } else {
        session_start();
        $_SESSION['message'] = "Gagal menghapus gudang.";
        $_SESSION['msg_type'] = "danger";
    }

    header("Location: index.php");
    exit;
}
?>

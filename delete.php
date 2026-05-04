<?php
require_once 'config/database.php';

// A. Validasi ID dari GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?msg=error_id");
    exit;
}

$id = $_GET['id'];

// Cek ID  
if (!is_numeric($id)) {
    header("Location: index.php?msg=invalid_id");
    exit;
}

// B. Cek keberadaan data di database
$stmt = $conn->prepare("SELECT id_kategori FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // kalau data tidak ditemukan
    header("Location: index.php?msg=not_found");
    exit;
}

// C. Proses Delete (prepared statement)
$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // cek apakah benar ada baris yang terhapus
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?msg=delete_success");
    } else {
        header("Location: index.php?msg=delete_failed");
    }
} else {
    header("Location: index.php?msg=error_query");
}

$stmt->close();
$conn->close();
exit;
?>
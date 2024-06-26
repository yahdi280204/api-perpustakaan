<?php
require 'db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM buku WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $buku = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($buku);
        } else {
            $stmt = $pdo->query("SELECT * FROM buku");
            $buku = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($buku);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['judul'], $data['pengarang'], $data['penerbit'], $data['tahun_terbit']]);
        echo json_encode(['message' => 'Buku berhasil ditambahkan']);
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?, tahun_terbit = ? WHERE id = ?");
            $stmt->execute([$data['judul'], $data['pengarang'], $data['penerbit'], $data['tahun_terbit'], $_GET['id']]);
            echo json_encode(['message' => 'Buku berhasil diperbarui']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("DELETE FROM buku WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode(['message' => 'Buku berhasil dihapus']);
        }
        break;

    default:
        echo json_encode(['message' => 'Permintaan tidak valid']);
        break;
}
?>

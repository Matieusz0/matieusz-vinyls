<?php
require 'php/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$albumsToDelete = $data['albums_to_delete'] ?? [];

if (!empty($albumsToDelete)) {
    // Fetch the image paths of the albums to delete
    $placeholders = implode(',', array_fill(0, count($albumsToDelete), '?'));
    $stmt = $pdo->prepare("SELECT zdjecie, zdjecie2 FROM albumy WHERE id IN ($placeholders)");
    $stmt->execute($albumsToDelete);
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Delete the albums from the database
    $stmt = $pdo->prepare("DELETE FROM albumy WHERE id IN ($placeholders)");
    $stmt->execute($albumsToDelete);

    // Delete the image files from the uploads folder
    foreach ($albums as $album) {
        if (file_exists($album['zdjecie'])) {
            unlink($album['zdjecie']);
        }
        if (file_exists($album['zdjecie2'])) {
            unlink($album['zdjecie2']);
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No albums selected']);
}
exit();
?>
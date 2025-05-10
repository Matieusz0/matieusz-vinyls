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

if (!is_array($albumsToDelete) || empty($albumsToDelete)) {
    echo json_encode(['success' => false, 'message' => 'Nie wybrano żadnych albumów do usunięcia.']);
    exit();
}

try {
    // Fetch the image paths of the albums to delete
    $placeholders = implode(',', array_fill(0, count($albumsToDelete), '?'));
    $stmt = $pdo->prepare("SELECT zdjecie FROM albumy WHERE id IN ($placeholders)");
    $stmt->execute($albumsToDelete);
    $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Delete the albums from the database
    $stmt = $pdo->prepare("DELETE FROM albumy WHERE id IN ($placeholders)");
    $stmt->execute($albumsToDelete);

    // Delete the image files from the uploads folder
    foreach ($albums as $album) {
        if (!empty($album['zdjecie']) && file_exists($album['zdjecie'])) {
            unlink($album['zdjecie']);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('Error during album deletion: ' . $e->getMessage()); // Debugging log
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas usuwania albumów.']);
}
exit();
?>

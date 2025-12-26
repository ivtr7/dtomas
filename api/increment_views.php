<?php
require_once '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_GET['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$db = getDB();
$videoId = intval($_GET['id']);

$stmt = $db->prepare("UPDATE videos SET views = views + 1 WHERE id = ?");
$stmt->execute([$videoId]);

echo json_encode(['success' => true]);
?>


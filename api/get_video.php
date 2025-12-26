<?php
require_once '../config.php';
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
    exit;
}

$db = getDB();
$videoId = intval($_GET['id']);

$stmt = $db->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$videoId]);
$video = $stmt->fetch();

if ($video) {
    echo json_encode(['success' => true, 'video' => $video]);
} else {
    echo json_encode(['success' => false, 'message' => 'Vídeo não encontrado']);
}
?>


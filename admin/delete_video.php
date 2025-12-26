<?php
require_once '../config.php';
requireLogin();

$db = getDB();

if (!isset($_GET['id'])) {
    header('Location: videos.php');
    exit;
}

$videoId = intval($_GET['id']);

// Buscar vídeo
$stmt = $db->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$videoId]);
$video = $stmt->fetch();

if ($video) {
    // Deletar arquivos físicos
    if ($video['thumbnail_url'] && file_exists('../' . $video['thumbnail_url'])) {
        unlink('../' . $video['thumbnail_url']);
    }
    
    if (strpos($video['video_url'], UPLOAD_DIR) === 0 && file_exists('../' . $video['video_url'])) {
        unlink('../' . $video['video_url']);
    }
    
    // Deletar do banco
    $stmt = $db->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->execute([$videoId]);
}

header('Location: videos.php');
exit;
?>


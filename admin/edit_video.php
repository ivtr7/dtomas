<?php
require_once '../config.php';
requireLogin();

$db = getDB();
$success = '';
$error = '';

if (!isset($_GET['id'])) {
    header('Location: videos.php');
    exit;
}

$videoId = intval($_GET['id']);

// Buscar vídeo
$stmt = $db->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$videoId]);
$video = $stmt->fetch();

if (!$video) {
    header('Location: videos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;
    $thumbnail_url = $video['thumbnail_url'];
    
    // Upload de nova thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['thumbnail'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($file['type'], $allowed)) {
            // Deletar thumbnail antiga se existir
            if ($thumbnail_url && file_exists('../' . $thumbnail_url)) {
                unlink('../' . $thumbnail_url);
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = '../' . THUMBNAIL_DIR . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $thumbnail_url = THUMBNAIL_DIR . $filename;
            }
        }
    }
    
    if (empty($title) || empty($category) || empty($video_url)) {
        $error = 'Preencha todos os campos obrigatórios!';
    } else {
        try {
            $stmt = $db->prepare("UPDATE videos SET title = ?, description = ?, category = ?, video_url = ?, thumbnail_url = ?, duration = ?, featured = ? WHERE id = ?");
            $stmt->execute([$title, $description, $category, $video_url, $thumbnail_url, $duration, $featured, $videoId]);
            $success = 'Vídeo atualizado com sucesso!';
            
            // Atualizar dados do vídeo
            $stmt = $db->prepare("SELECT * FROM videos WHERE id = ?");
            $stmt->execute([$videoId]);
            $video = $stmt->fetch();
        } catch (Exception $e) {
            $error = 'Erro ao atualizar vídeo: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vídeo - Admin <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-dashboard">
    <nav class="admin-nav">
        <div class="admin-nav-content">
            <div class="admin-logo">
                <span>THOMAS</span>
                <span class="admin-logo-sub">Admin</span>
            </div>
            <div class="admin-nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="videos.php" class="active">Vídeos</a>
                <a href="upload.php">Upload</a>
                <a href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Editar Vídeo</h1>
            <a href="videos.php" class="btn-secondary">← Voltar</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="admin-section">
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Título *</label>
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($video['title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Categoria *</label>
                        <select id="category" name="category" required>
                            <option value="Aftermovie Evento" <?php echo $video['category'] === 'Aftermovie Evento' ? 'selected' : ''; ?>>Aftermovie Evento</option>
                            <option value="Aftermovie DJ" <?php echo $video['category'] === 'Aftermovie DJ' ? 'selected' : ''; ?>>Aftermovie DJ</option>
                            <option value="Vídeo Drop" <?php echo $video['category'] === 'Vídeo Drop' ? 'selected' : ''; ?>>Vídeo Drop</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($video['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="video_url">URL do Vídeo *</label>
                    <input type="text" id="video_url" name="video_url" required value="<?php echo htmlspecialchars($video['video_url']); ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <?php if ($video['thumbnail_url']): ?>
                            <div class="current-thumbnail">
                                <img src="../<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="Thumbnail atual" style="max-width: 200px; margin-bottom: 10px; display: block;">
                                <small>Thumbnail atual</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <small>Deixe em branco para manter a atual</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duração (ex: 3:45)</label>
                        <input type="text" id="duration" name="duration" placeholder="3:45" value="<?php echo htmlspecialchars($video['duration']); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="featured" value="1" <?php echo $video['featured'] ? 'checked' : ''; ?>>
                        <span>Marcar como destaque</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Salvar Alterações</button>
                    <a href="videos.php" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


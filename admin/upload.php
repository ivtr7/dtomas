<?php
require_once '../config.php';
requireLogin();

$db = getDB();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    $thumbnail_url = '';
    
    // Upload de thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['thumbnail'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (in_array($file['type'], $allowed)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = '../' . THUMBNAIL_DIR . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $thumbnail_url = THUMBNAIL_DIR . $filename;
            }
        }
    }
    
    // Upload de vídeo (se for arquivo local)
    if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['video_file'];
        $allowed = ['video/mp4', 'video/webm', 'video/ogg'];
        
        if (in_array($file['type'], $allowed)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = '../' . UPLOAD_DIR . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $video_url = UPLOAD_DIR . $filename;
            }
        }
    }
    
    if (empty($title) || empty($category) || empty($video_url)) {
        $error = 'Preencha todos os campos obrigatórios!';
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO videos (title, description, category, video_url, thumbnail_url, duration, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $category, $video_url, $thumbnail_url, $duration, $featured]);
            $success = 'Vídeo cadastrado com sucesso!';
            
            // Limpar campos
            $_POST = [];
        } catch (Exception $e) {
            $error = 'Erro ao cadastrar vídeo: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Vídeo - Admin <?php echo SITE_NAME; ?></title>
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
                <a href="videos.php">Vídeos</a>
                <a href="upload.php" class="active">Upload</a>
                <a href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Upload de Vídeo</h1>
            <p>Adicione um novo vídeo ao portfólio</p>
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
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Categoria *</label>
                        <select id="category" name="category" required>
                            <option value="">Selecione...</option>
                            <option value="Aftermovie Evento" <?php echo (($_POST['category'] ?? '') === 'Aftermovie Evento') ? 'selected' : ''; ?>>Aftermovie Evento</option>
                            <option value="Aftermovie DJ" <?php echo (($_POST['category'] ?? '') === 'Aftermovie DJ') ? 'selected' : ''; ?>>Aftermovie DJ</option>
                            <option value="Vídeo Drop" <?php echo (($_POST['category'] ?? '') === 'Vídeo Drop') ? 'selected' : ''; ?>>Vídeo Drop</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="video_url">URL do Vídeo *</label>
                        <input type="text" id="video_url" name="video_url" placeholder="URL do YouTube, Vimeo ou caminho do arquivo" required value="<?php echo htmlspecialchars($_POST['video_url'] ?? ''); ?>">
                        <small>Ou faça upload de um arquivo abaixo</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_file">Upload de Vídeo (opcional)</label>
                        <input type="file" id="video_file" name="video_file" accept="video/*">
                        <small>Se enviar arquivo, a URL será ignorada</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duração (ex: 3:45)</label>
                        <input type="text" id="duration" name="duration" placeholder="3:45" value="<?php echo htmlspecialchars($_POST['duration'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="featured" value="1" <?php echo isset($_POST['featured']) ? 'checked' : ''; ?>>
                        <span>Marcar como destaque</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Salvar Vídeo</button>
                    <a href="dashboard.php" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


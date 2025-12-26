<?php
require_once '../config.php';
requireLogin();

$db = getDB();

// Buscar todos os vídeos
$stmt = $db->query("SELECT * FROM videos ORDER BY created_at DESC");
$videos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vídeos - Admin <?php echo SITE_NAME; ?></title>
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
            <h1>Gerenciar Vídeos</h1>
            <a href="upload.php" class="btn-primary">+ Novo Vídeo</a>
        </div>

        <div class="admin-section">
            <?php if (empty($videos)): ?>
                <div class="empty-state">
                    <p>Nenhum vídeo cadastrado ainda.</p>
                    <a href="upload.php" class="btn-primary">Adicionar Primeiro Vídeo</a>
                </div>
            <?php else: ?>
                <div class="videos-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Categoria</th>
                                <th>Visualizações</th>
                                <th>Destaque</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($videos as $video): ?>
                            <tr>
                                <td><?php echo $video['id']; ?></td>
                                <td><?php echo htmlspecialchars($video['title']); ?></td>
                                <td><span class="badge"><?php echo htmlspecialchars($video['category']); ?></span></td>
                                <td><?php echo number_format($video['views']); ?></td>
                                <td><?php echo $video['featured'] ? '⭐' : '-'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($video['created_at'])); ?></td>
                                <td>
                                    <a href="edit_video.php?id=<?php echo $video['id']; ?>" class="btn-edit">Editar</a>
                                    <a href="delete_video.php?id=<?php echo $video['id']; ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este vídeo?')">Excluir</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


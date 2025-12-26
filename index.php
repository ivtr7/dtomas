<?php
require_once 'config.php';
$db = getDB();

// Buscar vídeos por categoria
$categories = ['Aftermovie Evento', 'Aftermovie DJ', 'Vídeo Drop'];
$videos_by_category = [];

foreach ($categories as $category) {
    $stmt = $db->prepare("SELECT * FROM videos WHERE category = ? ORDER BY created_at DESC");
    $stmt->execute([$category]);
    $videos_by_category[$category] = $stmt->fetchAll();
}

// Buscar vídeos em destaque
$stmt = $db->prepare("SELECT * FROM videos WHERE featured = 1 ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$featured_videos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Portfólio de Aftermovies</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <span class="logo-text">THOMAS</span>
                <span class="logo-subtitle">videomaker</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Início</a></li>
                <li><a href="#videos" class="nav-link">Vídeos</a></li>
                <li><a href="#about" class="nav-link">Sobre</a></li>
                <li><a href="#contact" class="nav-link">Contato</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="glitch" data-text="THOMAS">THOMAS</span>
                    <span class="hero-subtitle">videomaker</span>
                </h1>
                <p class="hero-description">Capturando a energia e a emoção das maiores festas em aftermovies imersivos</p>
                <a href="#videos" class="btn-primary">Explorar Vídeos</a>
            </div>
            <div class="hero-particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
        </div>
        <div class="scroll-indicator">
            <div class="mouse"></div>
        </div>
    </section>

    <!-- Featured Videos -->
    <?php if (!empty($featured_videos)): ?>
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title">Em Destaque</h2>
            <div class="featured-grid">
                <?php foreach ($featured_videos as $video): ?>
                <div class="video-card featured">
                    <div class="video-thumbnail">
                        <?php if ($video['thumbnail_url']): ?>
                            <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                        <?php else: ?>
                            <div class="video-placeholder"></div>
                        <?php endif; ?>
                        <div class="play-overlay">
                            <div class="play-button"></div>
                        </div>
                        <div class="video-category"><?php echo htmlspecialchars($video['category']); ?></div>
                    </div>
                    <div class="video-info">
                        <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                        <?php if ($video['description']): ?>
                            <p><?php echo htmlspecialchars(substr($video['description'], 0, 100)); ?>...</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Videos by Category -->
    <section id="videos" class="videos-section">
        <div class="container">
            <?php foreach ($categories as $category): ?>
                <?php if (!empty($videos_by_category[$category])): ?>
                <div class="category-section">
                    <h2 class="category-title">
                        <span class="category-icon">▶</span>
                        <?php echo htmlspecialchars($category); ?>
                    </h2>
                    <div class="videos-grid">
                        <?php foreach ($videos_by_category[$category] as $video): ?>
                        <div class="video-card" data-video-id="<?php echo $video['id']; ?>">
                            <div class="video-thumbnail">
                                <?php if ($video['thumbnail_url']): ?>
                                    <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>">
                                <?php else: ?>
                                    <div class="video-placeholder"></div>
                                <?php endif; ?>
                                <div class="play-overlay">
                                    <div class="play-button"></div>
                                </div>
                                <?php if ($video['duration']): ?>
                                    <div class="video-duration"><?php echo htmlspecialchars($video['duration']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="video-info">
                                <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                                <?php if ($video['description']): ?>
                                    <p><?php echo htmlspecialchars(substr($video['description'], 0, 80)); ?>...</p>
                                <?php endif; ?>
                                <div class="video-stats">
                                    <span class="views">👁 <?php echo number_format($video['views']); ?> visualizações</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-content">
                <h2 class="section-title">Sobre</h2>
                <p class="about-text">
                    Especializado em capturar a essência e a energia das maiores festas e raves, 
                    transformando momentos em memórias visuais imersivas através de aftermovies 
                    cinematográficos que transmitem a emoção e a atmosfera única de cada evento.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title">Contato</h2>
            <div class="contact-content">
                <p>Entre em contato para orçamentos e parcerias</p>
                <div class="contact-info">
                    <a href="mailto:contato@thomasvideomaker.com" class="contact-link">📧 contato@thomasvideomaker.com</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Video Modal -->
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="videoPlayer"></div>
            <div id="videoDetails"></div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>


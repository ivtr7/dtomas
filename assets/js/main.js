// Mobile Menu Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
}

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Video Modal
const modal = document.getElementById('videoModal');
const videoCards = document.querySelectorAll('.video-card');
const closeModal = document.querySelector('.close-modal');
const videoPlayer = document.getElementById('videoPlayer');
const videoDetails = document.getElementById('videoDetails');

// Open modal when clicking on video card
videoCards.forEach(card => {
    card.addEventListener('click', function() {
        const videoId = this.getAttribute('data-video-id');
        if (videoId) {
            loadVideo(videoId);
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    });
});

// Close modal
if (closeModal) {
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        videoPlayer.innerHTML = '';
    });
}

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        videoPlayer.innerHTML = '';
    }
});

// Load video function
function loadVideo(videoId) {
    // Fetch video data
    fetch(`api/get_video.php?id=${videoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const video = data.video;
                
                // Create video element
                let videoHTML = '';
                if (video.video_url.includes('youtube.com') || video.video_url.includes('youtu.be')) {
                    // YouTube embed
                    const videoId = extractYouTubeId(video.video_url);
                    videoHTML = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                } else if (video.video_url.includes('vimeo.com')) {
                    // Vimeo embed
                    const videoId = extractVimeoId(video.video_url);
                    videoHTML = `<iframe src="https://player.vimeo.com/video/${videoId}?autoplay=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
                } else {
                    // Direct video file
                    videoHTML = `<video controls autoplay><source src="${video.video_url}" type="video/mp4">Seu navegador não suporta vídeos HTML5.</video>`;
                }
                
                videoPlayer.innerHTML = videoHTML;
                
                // Update video details
                videoDetails.innerHTML = `
                    <h3>${video.title}</h3>
                    ${video.description ? `<p>${video.description}</p>` : ''}
                    <div style="margin-top: 1rem; color: #888;">
                        <span>📁 ${video.category}</span>
                        ${video.duration ? ` | <span>⏱ ${video.duration}</span>` : ''}
                        <span> | 👁 ${video.views} visualizações</span>
                    </div>
                `;
                
                // Increment view count
                fetch(`api/increment_views.php?id=${videoId}`, { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar vídeo:', error);
            videoPlayer.innerHTML = '<p style="color: #00ff88; text-align: center; padding: 2rem;">Erro ao carregar vídeo. Tente novamente.</p>';
        });
}

// Extract YouTube video ID
function extractYouTubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}

// Extract Vimeo video ID
function extractVimeoId(url) {
    const regExp = /(?:vimeo)\.com.*(?:videos|video|channels|)\/([\d]+)/i;
    const match = url.match(regExp);
    return match ? match[1] : null;
}

// Navbar scroll effect
let lastScroll = 0;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll <= 0) {
        navbar.style.boxShadow = 'none';
    } else {
        navbar.style.boxShadow = '0 2px 20px rgba(0, 255, 136, 0.1)';
    }
    
    lastScroll = currentScroll;
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe video cards
document.querySelectorAll('.video-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});


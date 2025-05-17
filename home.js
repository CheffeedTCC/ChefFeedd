document.addEventListener('DOMContentLoaded', function() {
    // Initialize icons
    lucide.createIcons();
    
    // Sidebar toggle
    let btn = document.querySelector("#btn");
    let sidebar = document.querySelector(".sidebar");
    let searchBtn = document.querySelector(".bx-search");

    btn.onclick = function() {
        sidebar.classList.toggle("active");
    }

    searchBtn.onclick = function() {
        sidebar.classList.toggle("active");
    }

    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    
    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        darkModeToggle.checked = true;
    }
    
    darkModeToggle.addEventListener('change', function() {
        const isDarkMode = document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', isDarkMode);
    });
    
    // Search functionality
    const searchBar = document.getElementById('search-bar');
    const searchResults = document.getElementById('search-results');
    
    searchBar.addEventListener('input', async function() {
        const query = this.value.trim();
        
        if (query.length > 0) {
            try {
                const response = await fetch(`api/search.php?q=${encodeURIComponent(query)}`);
                const results = await response.json();
                
                if (results.length > 0) {
                    searchResults.innerHTML = results.map(user => `
                        <a href="perfil.php?id=${user.id}" class="result-item">
                            <img src="${user.pp || 'imgf/default-profile.png'}" alt="${user.username}">
                            <span>${user.username}</span>
                        </a>
                    `).join('');
                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = '<div class="result-item">Nenhum resultado encontrado</div>';
                    searchResults.style.display = 'block';
                }
            } catch (error) {
                console.error('Erro na busca:', error);
                searchResults.innerHTML = '<div class="result-item">Erro na busca</div>';
                searchResults.style.display = 'block';
            }
        } else {
            searchResults.style.display = 'none';
        }
    });
    
    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.busca-container')) {
            searchResults.style.display = 'none';
        }
    });
    
    // Like button functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const post = this.closest('.post');
            const postId = post.dataset.postId;
            const likesCount = post.querySelector('.likes-count');
            const icon = this.querySelector('i');
            
            if (this.classList.contains('liked')) {
                // Unlike
                try {
                    const response = await fetch('api/unlike.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId })
                    });
                    
                    if (response.ok) {
                        this.classList.remove('liked');
                        icon.classList.replace('bxs-heart', 'bx-heart');
                        likesCount.textContent = parseInt(likesCount.textContent) - 1;
                    }
                } catch (error) {
                    console.error('Erro ao descurtir:', error);
                }
            } else {
                // Like
                try {
                    const response = await fetch('api/like.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId })
                    });
                    
                    if (response.ok) {
                        this.classList.add('liked');
                        icon.classList.replace('bx-heart', 'bxs-heart');
                        likesCount.textContent = parseInt(likesCount.textContent) + 1;
                        
                        // Add heart animation
                        const heart = document.createElement('div');
                        heart.className = 'heart-animation';
                        heart.innerHTML = '❤️';
                        this.appendChild(heart);
                        
                        setTimeout(() => {
                            heart.remove();
                        }, 1000);
                    }
                } catch (error) {
                    console.error('Erro ao curtir:', error);
                }
            }
        });
    });
    
    // Double tap to like on post image
    document.querySelectorAll('.post-image').forEach(image => {
        image.addEventListener('dblclick', function() {
            const post = this.closest('.post');
            const likeBtn = post.querySelector('.like-btn');
            likeBtn.click();
            
            // Show temporary heart animation
            const heart = document.createElement('div');
            heart.className = 'heart-animation';
            heart.innerHTML = '❤️';
            heart.style.position = 'absolute';
            heart.style.left = '50%';
            heart.style.top = '50%';
            heart.style.transform = 'translate(-50%, -50%)';
            heart.style.fontSize = '60px';
            heart.style.opacity = '0.8';
            this.parentNode.appendChild(heart);
            
            setTimeout(() => {
                heart.remove();
            }, 1000);
        });
    });
    
    // Follow button functionality
    document.querySelectorAll('.follow-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const userId = this.getAttribute('data-user-id');
            
            if (this.classList.contains('following')) {
                // Unfollow
                try {
                    const response = await fetch('api/unfollow.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ user_id: userId })
                    });
                    
                    if (response.ok) {
                        this.classList.remove('following');
                        this.textContent = 'Seguir';
                    }
                } catch (error) {
                    console.error('Erro ao deixar de seguir:', error);
                }
            } else {
                // Follow
                try {
                    const response = await fetch('api/follow.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ user_id: userId })
                    });
                    
                    if (response.ok) {
                        this.classList.add('following');
                        this.textContent = 'Seguindo';
                    }
                } catch (error) {
                    console.error('Erro ao seguir:', error);
                }
            }
        });
    });
    
    // Save button functionality
    document.querySelectorAll('.save-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const post = this.closest('.post');
            const postId = post.dataset.postId;
            const icon = this.querySelector('i');
            
            if (this.classList.contains('saved')) {
                // Unsaved
                try {
                    const response = await fetch('api/unsave.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId })
                    });
                    
                    if (response.ok) {
                        this.classList.remove('saved');
                        icon.classList.replace('bxs-bookmark', 'bx-bookmark');
                    }
                } catch (error) {
                    console.error('Erro ao remover dos salvos:', error);
                }
            } else {
                // Saved
                try {
                    const response = await fetch('api/save.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ post_id: postId })
                    });
                    
                    if (response.ok) {
                        this.classList.add('saved');
                        icon.classList.replace('bx-bookmark', 'bxs-bookmark');
                    }
                } catch (error) {
                    console.error('Erro ao salvar:', error);
                }
            }
        });
    });
    
    // Post options menu
    document.querySelectorAll('.options-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.nextElementSibling;
            
            // Close all other open menus
            document.querySelectorAll('.options-menu').forEach(m => {
                if (m !== menu) m.style.display = 'none';
            });
            
            // Toggle current menu
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        });
    });
    
    // Close menus when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.options-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });
    
    // Comment functionality
    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('input', function() {
            const postBtn = this.nextElementSibling;
            if (this.value.trim().length > 0) {
                postBtn.classList.add('active');
            } else {
                postBtn.classList.remove('active');
            }
        });
        
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim().length > 0) {
                this.nextElementSibling.click();
            }
        });
    });
    
    document.querySelectorAll('.post-comment-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const input = this.previousElementSibling;
            const comment = input.value.trim();
            const post = this.closest('.post');
            const postId = post.dataset.postId;
            
            if (comment.length > 0) {
                try {
                    const response = await fetch('api/comment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            post_id: postId,
                            comment: comment
                        })
                    });
                    
                    if (response.ok) {
                        const commentsSection = post.querySelector('.post-comments');
                        
                        // Clear input
                        input.value = '';
                        this.classList.remove('active');
                        
                        // Update comments count
                        const viewComments = commentsSection.querySelector('.view-comments');
                        if (viewComments) {
                            const currentText = viewComments.textContent;
                            if (currentText.includes('Ver todos os')) {
                                const countMatch = currentText.match(/\d+/);
                                const count = countMatch ? parseInt(countMatch[0]) + 1 : 1;
                                viewComments.textContent = `Ver todos os ${count} comentários`;
                            } else {
                                viewComments.textContent = 'Ver todos os 1 comentário';
                            }
                        }
                    }
                } catch (error) {
                    console.error('Erro ao postar comentário:', error);
                }
            }
        });
    });
    
    // Modal functionality
    const settingsModal = document.getElementById('settings-modal');
    const reportModal = document.getElementById('report-modal');
    
    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === settingsModal) {
            settingsModal.style.display = 'none';
        }
        if (e.target === reportModal) {
            reportModal.style.display = 'none';
        }
    });
    
    // Prevent modal content clicks from closing modal
    document.querySelectorAll('.modal-content').forEach(content => {
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // Report post function
    window.reportPost = function(postId) {
        document.getElementById('report-post-id').value = postId;
        reportModal.style.display = 'flex';
    };
    
    // Handle report form submission
    document.getElementById('report-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const postId = document.getElementById('report-post-id').value;
        const reason = document.getElementById('report-reason').value;
        const details = document.getElementById('report-details').value;
        
        try {
            const response = await fetch('api/report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    post_id: postId,
                    reason: reason,
                    details: details
                })
            });
            
            if (response.ok) {
                alert('Denúncia enviada com sucesso!');
                reportModal.style.display = 'none';
            } else {
                alert('Erro ao enviar denúncia');
            }
        } catch (error) {
            console.error('Erro ao enviar denúncia:', error);
            alert('Erro ao enviar denúncia');
        }
    });
    
    // Delete post function
    window.deletePost = async function(postId) {
        if (confirm('Tem certeza que deseja excluir esta postagem?')) {
            try {
                const response = await fetch('api/delete_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ post_id: postId })
                });
                
                if (response.ok) {
                    document.querySelector(`.post[data-post-id="${postId}"]`).remove();
                } else {
                    alert('Erro ao excluir postagem');
                }
            } catch (error) {
                console.error('Erro ao excluir postagem:', error);
                alert('Erro ao excluir postagem');
            }
        }
    };

    // Create floating emojis
    function createFloatingEmoji() {
        const emojis = ['🍕', '🍔', '🍟', '🌭', '🍿', '🥓', '🥞', '🧀', '🍗', '🍖', '🌮', '🌯', '🍳', '🍲', '🥗', '🍝', '🍜', '🍣', '🍤', '🍥'];
        const emoji = document.createElement('div');
        emoji.className = 'floating-emoji';
        emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
        
        // Random position
        emoji.style.left = Math.random() * 100 + 'vw';
        emoji.style.bottom = '0';
        
        // Random size
        const size = Math.random() * 20 + 10;
        emoji.style.fontSize = size + 'px';
        
        // Random animation duration
        const duration = Math.random() * 10 + 5;
        emoji.style.animationDuration = duration + 's';
        
        document.querySelector('.emoji-bg').appendChild(emoji);
        
        // Remove emoji after animation completes
        setTimeout(() => {
            emoji.remove();
        }, duration * 1000);
    }
    
    // Create emojis periodically
    setInterval(createFloatingEmoji, 500);
});

// Heart animation style
const heartStyle = document.createElement('style');
heartStyle.textContent = `
    @keyframes floatUp {
        0% {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        100% {
            transform: translateY(-50px) scale(1.5);
            opacity: 0;
        }
    }
    
    .heart-animation {
        position: absolute;
        animation: floatUp 1s ease-out forwards;
        pointer-events: none;
        z-index: 10;
    }
    
    @keyframes floating {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(-100vh) rotate(360deg);
            opacity: 0;
        }
    }
    
    .floating-emoji {
        position: fixed;
        animation: floating linear forwards;
        pointer-events: none;
        z-index: -1;
    }
`;
document.head.appendChild(heartStyle);
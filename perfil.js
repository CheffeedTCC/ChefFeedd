document.addEventListener('DOMContentLoaded', function () {
    // Inicializa os ícones do Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    // Função para atualizar contadores dinamicamente
function updateCounters(type, increment = true) {
    const counters = {
        'posts': document.querySelector('.profile-stats span:nth-child(1) b'),
        'followers': document.querySelector('.profile-stats span:nth-child(2) b'),
        'following': document.querySelector('.profile-stats span:nth-child(3) b')
    };

    if (counters[type]) {
        let currentValue = parseInt(counters[type].textContent);
        counters[type].textContent = increment ? currentValue + 1 : currentValue - 1;
    }
}

// Atualizar contador de publicações quando uma nova for criada
// Isso deve ser chamado quando você criar uma nova postagem
function incrementPostCount() {
    updateCounters('posts');
}

// Atualizar contador de seguidores quando alguém seguir
function incrementFollowersCount() {
    updateCounters('followers');
}

// Atualizar contador de seguindo quando você seguir alguém
function incrementFollowingCount() {
    updateCounters('following');
}});
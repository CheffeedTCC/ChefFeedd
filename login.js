document.addEventListener('DOMContentLoaded', function () {
    // Inicializa os ícones do Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Dados das postagens
    const posts = [
        {
            profilePic: 'imgf/maria.png', // Caminho ajustado para a pasta imgf
            username: 'chef_maria',
            image: 'https://bolonahora.com.br/web/image/product.template/137/image_1024?unique=db365b7',
            caption: 'Bolo de Chocolate 🍫🍰'
        },
        {
            profilePic: 'imgf/carlos.png', // Caminho ajustado para a pasta imgf
            username: 'chef_carlos',
            image: 'https://images.unsplash.com/photo-1626844131082-256783844137?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            caption: 'Espaguete à Bolonhesa🍝'
        },
        {
            profilePic: 'imgf/Julia.png', // Caminho ajustado para a pasta imgf
            username: 'chef_julia08',
            image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1480&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            caption: 'Salada de Legumes🥗✨'
        }
    ];

    let currentPostIndex = 0;

    // Função para atualizar a postagem com transição
    function updatePost() {
        const post = posts[currentPostIndex];

        // Seleciona os elementos que serão animados
        const postContent = document.querySelector('.feed-post');
        const profilePic = document.getElementById('profile-pic');
        const username = document.getElementById('username');
        const postImage = document.getElementById('post-image');
        const captionText = document.querySelector('.caption-text');
        const captionUsername = document.getElementById('caption-username');

        if (postContent && profilePic && username && postImage && captionText && captionUsername) {
            // Adiciona a classe de fade out para iniciar a animação de saída
            postContent.classList.add('fade-out');

            // Aguarda o término da animação de saída antes de trocar o conteúdo
            setTimeout(() => {
                // Atualiza o conteúdo
                profilePic.src = post.profilePic;
                username.textContent = post.username;
                postImage.src = post.image;
                captionText.textContent = post.caption;
                captionUsername.textContent = post.username + ':';

                // Remove a classe de fade out e adiciona a classe de fade in para animar a entrada
                postContent.classList.remove('fade-out');
                postContent.classList.add('fade-in');

                // Remove a classe de fade in após a animação
                setTimeout(() => {
                    postContent.classList.remove('fade-in');
                }, 500); // Tempo correspondente à duração da animação
            }, 500); // Tempo correspondente à duração da animação

            // Atualiza o índice para a próxima postagem
            currentPostIndex = (currentPostIndex + 1) % posts.length;
        }
    }

    // Atualiza a postagem a cada 5 segundos (5000 ms)
    setInterval(updatePost, 5000);

    // Adiciona efeitos de hover para os ícones de ação
    const actionIcons = [
        { selector: '[data-lucide="heart"]', hoverColor: '#ef4444' }, // Vermelho
        { selector: '[data-lucide="message-circle"]', hoverColor: '#3b82f6' }, // Azul
        { selector: '[data-lucide="bookmark"]', hoverColor: '#f59e0b' } // Amarelo
    ];

    actionIcons.forEach(icon => {
        const element = document.querySelector(icon.selector);
        if (element) {
            element.addEventListener('mouseover', function () {
                this.style.color = icon.hoverColor;
            });
            element.addEventListener('mouseout', function () {
                this.style.color = '#374151'; // Volta à cor padrão
            });
        }
    });
});
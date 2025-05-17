document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const filtersContainer = document.getElementById('filtersContainer');
    const recipesContainer = document.getElementById('recipesContainer');
    const applyFiltersButton = document.getElementById('applyFilters');
    
    // Emojis flutuantes
    createFloatingEmojis();
    
    // Carregar receitas ao iniciar
    loadRecipes();
    
    // Evento de clique/foco na barra de pesquisa
    searchInput.addEventListener('focus', function() {
        filtersContainer.style.display = 'block';
    });
    
    // Evento de pesquisa
    searchButton.addEventListener('click', searchRecipes);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchRecipes();
        }
    });
    
    // Evento de aplicar filtros
    applyFiltersButton.addEventListener('click', searchRecipes);
    
    // Função para criar emojis flutuantes
    function createFloatingEmojis() {
        const emojiContainer = document.querySelector('.emoji-bg');
        const emojis = ['🍎', '🍐', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🍈', '🍒', '🍑', '🥭', '🍍', '🥥', '🥝', '🍅', '🥑', '🥦', '🥬', '🥒', '🌶', '🌽', '🥕', '🧄', '🧅', '🥔', '🍠', '🥐', '🥯', '🍞', '🥖', '🥨', '🧀', '🥚', '🍳', '🧈', '🥞', '🧇', '🥓', '🥩', '🍗', '🍖', '🦴', '🌭', '🍔', '🍟', '🍕', '🥪', '🥙', '🧆', '🌮', '🌯', '🥗', '🥘', '🥫', '🍝', '🍜', '🍲', '🍛', '🍣', '🍱', '🥟', '🍤', '🍙', '🍚', '🍘', '🍥', '🥠', '🥮', '🍢', '🍡', '🍧', '🍨', '🍦', '🥧', '🧁', '🍰', '🎂', '🍮', '🍭', '🍬', '🍫', '🍿', '🍩', '🍪', '🌰', '🥜', '🍯'];
        
        // Limpa emojis existentes
        emojiContainer.innerHTML = '';
        
        // Adiciona 20 emojis aleatórios
        for (let i = 0; i < 20; i++) {
            const emoji = document.createElement('span');
            emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            
            // Posição aleatória
            emoji.style.left = `${Math.random() * 100}%`;
            
            // Atraso e duração aleatórios
            emoji.style.animationDelay = `${Math.random() * 20}s`;
            emoji.style.animationDuration = `${10 + Math.random() * 15}s`;
            
            // Tamanho aleatório
            const size = 1 + Math.random() * 2;
            emoji.style.fontSize = `${size}rem`;
            
            emojiContainer.appendChild(emoji);
        }
    }
    
    // Função para carregar receitas
    function loadRecipes() {
        // Simulação de carregamento de dados
        setTimeout(() => {
            const mockRecipes = [
                {
                    id: 1,
                    title: "Bolo de Chocolate",
                    description: "Clássico bolo de chocolate fofinho, perfeito para qualquer ocasião.",
                    prep_time: 15,
                    cook_time: 40,
                    servings: 8,
                    category: "doce",
                    difficulty: "fácil",
                    image_url: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                },
                {
                    id: 2,
                    title: "Feijoada Completa",
                    description: "Tradicional feijoada brasileira com todas as delícias.",
                    prep_time: 30,
                    cook_time: 120,
                    servings: 6,
                    category: "salgado",
                    difficulty: "médio",
                    image_url: "https://images.unsplash.com/photo-1547592180-85f173990554?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                },
                {
                    id: 3,
                    title: "Pão de Queijo",
                    description: "Tradicional receita mineira, crocante por fora e macio por dentro.",
                    prep_time: 20,
                    cook_time: 20,
                    servings: 30,
                    category: "ambos",
                    difficulty: "fácil",
                    image_url: "https://images.unsplash.com/photo-1601758003122-53c40e686a19?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                },
                {
                    id: 4,
                    title: "Lasanha à Bolonhesa",
                    description: "Deliciosa lasanha com molho à bolonhesa e queijo derretido.",
                    prep_time: 30,
                    cook_time: 45,
                    servings: 6,
                    category: "salgado",
                    difficulty: "médio",
                    image_url: "https://images.unsplash.com/photo-1629115916087-7e8c114a24ed?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                },
                {
                    id: 5,
                    title: "Pudim de Leite",
                    description: "Sobremesa cremosa e deliciosa, perfeita para qualquer ocasião.",
                    prep_time: 15,
                    cook_time: 60,
                    servings: 8,
                    category: "doce",
                    difficulty: "médio",
                    image_url: "https://images.unsplash.com/photo-1562440499-64c9a111f713?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                },
                {
                    id: 6,
                    title: "Strogonoff de Frango",
                    description: "Clássico strogonoff cremoso, perfeito para almoços em família.",
                    prep_time: 20,
                    cook_time: 25,
                    servings: 4,
                    category: "salgado",
                    difficulty: "fácil",
                    image_url: "https://images.unsplash.com/photo-1631452180519-c014fe946bc7?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                }
            ];
            
            displayRecipes(mockRecipes);
        }, 1000);
    }
    
    // Função para pesquisar receitas
    function searchRecipes() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const category = document.getElementById('category').value;
        const difficulty = document.getElementById('difficulty').value;
        const time = parseInt(document.getElementById('time').value) || 0;
        
        // Mostrar esqueleto de carregamento
        recipesContainer.innerHTML = '';
        for (let i = 0; i < 3; i++) {
            recipesContainer.innerHTML += '<div class="skeleton-card"></div>';
        }
        
        // Simulação de busca
        setTimeout(() => {
            // Em uma implementação real, você faria uma requisição AJAX aqui
            loadRecipes();
            
            // Filtra as receitas localmente (simulação)
            const recipeCards = document.querySelectorAll('.recipe-card');
            recipeCards.forEach(card => {
                const title = card.querySelector('.recipe-title').textContent.toLowerCase();
                const desc = card.querySelector('.recipe-description').textContent.toLowerCase();
                const recipeCategory = card.querySelector('.recipe-category').textContent.toLowerCase();
                const totalTime = parseInt(card.querySelector('.recipe-meta span:first-child').textContent.split(' ')[0]);
                const recipeDifficulty = card.querySelector('.recipe-meta span:nth-child(2)').textContent.toLowerCase();
                
                const matchesSearch = searchTerm === '' || 
                    title.includes(searchTerm) || 
                    desc.includes(searchTerm);
                
                const matchesCategory = category === 'all' || 
                    recipeCategory.includes(category);
                
                const matchesDifficulty = difficulty === 'all' || 
                    recipeDifficulty.includes(difficulty);
                
                const matchesTime = time === 0 || 
                    totalTime <= time;
                
                if (!(matchesSearch && matchesCategory && matchesDifficulty && matchesTime)) {
                    card.style.display = 'none';
                }
            });
        }, 500);
    }
    
    // Função para exibir receitas
    function displayRecipes(recipes) {
        recipesContainer.innerHTML = '';
        
        if (recipes.length === 0) {
            recipesContainer.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-utensils"></i>
                    <p>Nenhuma receita encontrada. Tente outros termos de pesquisa.</p>
                </div>
            `;
            return;
        }
        
        recipes.forEach(recipe => {
            const recipeCard = document.createElement('div');
            recipeCard.className = 'recipe-card';
            
            // Imagem padrão baseada na categoria
            let imageUrl = recipe.image_url || 'https://source.unsplash.com/random/300x200/?food';
            
            // Classe de categoria
            const categoryClass = `category-${recipe.category}`;
            
            // Tempo total
            const totalTime = (recipe.prep_time || 0) + (recipe.cook_time || 0);
            
            recipeCard.innerHTML = `
                <div class="recipe-image" style="background-image: url('${imageUrl}')"></div>
                <div class="recipe-info">
                    <h3 class="recipe-title">${recipe.title}</h3>
                    <p class="recipe-description">${recipe.description || 'Deliciosa receita para qualquer ocasião.'}</p>
                    <div class="recipe-meta">
                        <span><i class="far fa-clock"></i> ${totalTime} min</span>
                        <span><i class="fas fa-utensils"></i> ${recipe.servings} porções</span>
                        <span class="recipe-category ${categoryClass}">${recipe.category}</span>
                    </div>
                    <a href="#" class="view-recipe" data-id="${recipe.id}">Ver Receita Completa</a>
                </div>
            `;
            
            recipesContainer.appendChild(recipeCard);
        });
        
        // Adiciona eventos para os botões "Ver Receita"
        document.querySelectorAll('.view-recipe').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const recipeId = this.getAttribute('data-id');
                viewRecipeDetails(recipeId);
            });
        });
    }
    
    // Função para visualizar detalhes da receita
    function viewRecipeDetails(recipeId) {
        alert(`Detalhes da receita com ID ${recipeId} seriam exibidos aqui em um modal ou página separada.`);
    }
});
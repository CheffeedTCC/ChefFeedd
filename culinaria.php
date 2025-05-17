<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {

    include "../db_conn.php";
    include 'php/User.php';
    $user = getUserById($_SESSION['id'], $conn);


?>

    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Culinária | Encontre as melhores receitas</title>
        <link rel="stylesheet" href="css/culinaria.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            /* Floating emojis background */
            .emoji-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                opacity: 0.05;
                overflow: hidden;
            }

            .emoji-bg span {
                position: absolute;
                font-size: 1.5rem;
                animation: float 10s linear infinite;
                opacity: 0;
                user-select: none;
            }

            @keyframes float {
                0% {
                    transform: translateY(100vh) scale(0.5);
                    opacity: 0;
                }

                10% {
                    opacity: 0.5;
                }

                90% {
                    opacity: 0.5;
                }

                100% {
                    transform: translateY(-10vh) scale(1);
                    opacity: 0;
                }
            }

            /* Search improvements */
            .search-bar:focus-within {
                transform: scale(1.02);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .search-bar input::placeholder {
                transition: opacity 0.3s;
            }

            .search-bar input:focus::placeholder {
                opacity: 0.5;
            }

            /* Recipe cards hover effect */
            .recipe-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>


    <a href="home.php">Página Incial</a>

    <body>
        <div class="emoji-bg" id="emoji-bg"></div>

        <header>
            <h1 class="title">Culinária</h1>
            <p class="subtitle">

                a receitas incríveis para qualquer ocasião</p>
        </header>

        <?php if ($user) { ?>

        <?php } else {
            header("Location: login.php");
            exit;
        } ?>

        <main>
            <div class="search-container">
                <div class="search-bar" id="searchBar">
                    <input type="text" placeholder="Pesquise por receitas..." id="searchInput">
                    <button id="searchButton">
                        <i class="fas fa-search"></i>
                        <span>Buscar</span>
                    </button>
                </div>

                <div class="filters-container" id="filtersContainer">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="category"><i class="fas fa-utensils"></i> Categoria</label>
                            <select id="category">
                                <option value="all">Todas Categorias</option>
                                <option value="doce">Doces</option>
                                <option value="salgado">Salgados</option>
                                <option value="ambos">Ambos</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="difficulty"><i class="fas fa-signal"></i> Dificuldade</label>
                            <select id="difficulty">
                                <option value="all">Qualquer nível</option>
                                <option value="fácil">Fácil</option>
                                <option value="médio">Médio</option>
                                <option value="difícil">Difícil</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-row">
                        <div class="filter-group time-filter">
                            <label for="time"><i class="far fa-clock"></i> Tempo máximo</label>
                            <div class="time-input-container">
                                <input type="number" id="time" min="0" placeholder="Minutos">
                                <span>min</span>
                            </div>
                        </div>

                        <button class="apply-filters" id="applyFilters">
                            <i class="fas fa-filter"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <div class="recipes-container" id="recipesContainer">
                <!-- As receitas serão carregadas aqui via JavaScript -->
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
                <div class="skeleton-card"></div>
            </div>
        </main>

        <footer>
            <p>TCC - Site de Culinária &copy; <span id="current-year"></span></p>
            <p>Desenvolvido com <i class="fas fa-heart"></i> para seu projeto</p>
        </footer>

        <script src="js/culinaria.js"></script>
        <script>
            // Create floating emojis
            document.addEventListener('DOMContentLoaded', function() {
                const emojis = ['🍎', '🍐', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🍈', '🍒', '🍑', '🥭', '🍍', '🥥', '🥝', '🍅', '🥑', '🥦', '🥬', '🥒', '🌶', '🌽', '🥕', '🧄', '🧅', '🥔', '🍠', '🥐', '🥯', '🍞', '🥖', '🥨', '🧀', '🥚', '🍳', '🧈', '🥞', '🧇', '🥓', '🥩', '🍗', '🍖', '🦴', '🌭', '🍔', '🍟', '🍕', '🥪', '🥙', '🧆', '🌮', '🌯', '🥗', '🥘', '🥫', '🍝', '🍜', '🍲', '🍛', '🍣', '🍱', '🥟', '🍤', '🍙', '🍚', '🍘', '🍥', '🥠', '🥮', '🍢', '🍡', '🍧', '🍨', '🍦', '🥧', '🧁', '🍰', '🎂', '🍮', '🍭', '🍬', '🍫', '🍿', '🍩', '🍪', '🌰', '🥜', '🍯'];
                const container = document.getElementById('emoji-bg');

                for (let i = 0; i < 20; i++) {
                    const emoji = document.createElement('span');
                    emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];

                    // Random position
                    emoji.style.left = `${Math.random() * 100}%`;

                    // Random animation
                    const duration = 10 + Math.random() * 20;
                    const delay = Math.random() * 20;
                    emoji.style.animationDuration = `${duration}s`;
                    emoji.style.animationDelay = `${delay}s`;

                    // Random size
                    const size = 1 + Math.random() * 2;
                    emoji.style.fontSize = `${size}rem`;

                    container.appendChild(emoji);
                }

                // Set current year
                document.getElementById('current-year').textContent = new Date().getFullYear();
            });
        </script>



    </body>

    </html>

<?php } else {
    header("Location: login.php");
    exit;
} ?>
<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {

    include "../db_conn.php";
    include 'php/User.php';
    $user = getUserById($_SESSION['id'], $conn);


?>


    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chef Game Zone</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            :root {
                --primary-color: #00ff00;
                --secondary-color: #00cc88;
                --accent-color: #ff6b00;
                --coin-color: #FFD700;
                --dark-bg: #0f0f1a;
                --darker-bg: #0a0a12;
                --card-bg: rgba(0, 0, 0, 0.7);
                --legendary-gradient: linear-gradient(45deg, #ff8a00, #e52e71, #b36bff);
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Courier New', monospace;
                background-color: var(--dark-bg);
                color: var(--primary-color);
                min-height: 100vh;
                background-image:
                    linear-gradient(rgba(15, 15, 26, 0.9), rgba(15, 15, 26, 0.9)),
                    url('img/game-grid.png');
            }

            /* Header */
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 5%;
                background-color: var(--darker-bg);
                border-bottom: 2px solid var(--primary-color);
                box-shadow: 0 0 20px rgba(0, 255, 0, 0.2);
                position: relative;
            }

            .logo-container {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
            }

            .logo {
                font-size: 2rem;
                font-weight: bold;
                text-shadow: 0 0 10px var(--primary-color);
            }

            .logo-slogan {
                font-size: 0.8rem;
                color: var(--secondary-color);
                margin-top: 5px;
            }

            .auth-section {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .auth-button {
                padding: 8px 15px;
                background: var(--primary-color);
                color: #000;
                border: none;
                border-radius: 5px;
                font-family: 'Courier New', monospace;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s;
            }

            .auth-button:hover {
                background: var(--secondary-color);
                box-shadow: 0 0 10px var(--primary-color);
            }

            /* User Profile */
            .user-profile {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .user-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                border: 2px solid var(--primary-color);
                background-size: cover;
                position: relative;
                overflow: hidden;
            }

            .user-level-badge {
                position: absolute;
                bottom: -5px;
                right: -5px;
                background: var(--accent-color);
                color: #000;
                width: 25px;
                height: 25px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 0.8rem;
                border: 2px solid var(--darker-bg);
            }

            .user-info {
                display: flex;
                flex-direction: column;
            }

            .user-name {
                font-weight: bold;
                color: var(--secondary-color);
            }

            .user-xp {
                font-size: 0.8rem;
                color: var(--primary-color);
            }

            .user-coins {
                font-size: 0.8rem;
                color: var(--coin-color);
                display: flex;
                align-items: center;
            }

            .user-coins i {
                margin-right: 5px;
            }

            /* Games Grid */
            .main-container {
                display: flex;
                padding: 30px 5%;
                gap: 30px;
            }

            .games-section {
                flex: 3;
            }

            .sidebar {
                flex: 1;
            }

            .section-title {
                font-size: 1.8rem;
                margin-bottom: 20px;
                color: var(--secondary-color);
                text-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
                position: relative;
                display: inline-block;
            }

            .section-title::after {
                content: '';
                position: absolute;
                bottom: -5px;
                left: 0;
                width: 100%;
                height: 2px;
                background: linear-gradient(90deg, var(--primary-color), transparent);
            }

            .games-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 25px;
            }

            /* Cartões de jogo */
            .game-card {
                position: relative;
                width: 100%;
                aspect-ratio: 1/1;
                overflow: hidden;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 255, 0, 0.3);
                transition: all 0.3s;
            }

            .game-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s;
            }

            .game-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 20px;
                opacity: 0;
                transition: opacity 0.3s;
            }

            .game-card:hover .game-overlay {
                opacity: 1;
            }

            .game-card:hover .game-image {
                transform: scale(1.1);
            }

            .game-title {
                font-size: 1.5rem;
                color: var(--secondary-color);
                margin-bottom: 10px;
            }

            .game-description {
                color: #ccc;
                margin-bottom: 15px;
                font-size: 0.9rem;
                flex-grow: 1;
            }

            .game-rating {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }

            .stars {
                color: gold;
                margin-right: 10px;
            }

            .rating-value {
                color: var(--secondary-color);
            }

            .play-button {
                display: block;
                padding: 10px;
                background: var(--primary-color);
                color: #000;
                text-align: center;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                transition: all 0.3s;
            }

            .play-button:hover {
                background: var(--secondary-color);
                box-shadow: 0 0 10px var(--primary-color);
            }

            /* Sidebar */
            .sidebar-card {
                background: var(--card-bg);
                border: 2px solid var(--primary-color);
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 25px;
            }

            .card-title {
                font-size: 1.2rem;
                color: var(--secondary-color);
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid var(--primary-color);
            }

            /* Botões */
            .action-button {
                width: 100%;
                padding: 12px;
                background: var(--primary-color);
                color: #000;
                border: none;
                border-radius: 5px;
                font-family: 'Courier New', monospace;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s;
                margin-bottom: 15px;
                text-align: center;
                display: block;
                text-decoration: none;
            }

            .action-button:hover {
                background: var(--secondary-color);
                box-shadow: 0 0 10px var(--primary-color);
            }

            .shop-button {
                background: var(--coin-color);
                color: #000;
            }

            .shop-button:hover {
                background: #ffcc00;
                box-shadow: 0 0 10px var(--coin-color);
            }

            .settings-button {
                background: var(--accent-color);
            }

            .settings-button:hover {
                background: #ff8c00;
                box-shadow: 0 0 10px var(--accent-color);
            }

            /* Modal */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                overflow-y: auto;
            }

            .modal-content {
                background-color: var(--darker-bg);
                margin: 5% auto;
                padding: 25px;
                border: 2px solid var(--primary-color);
                border-radius: 10px;
                width: 80%;
                max-width: 800px;
                box-shadow: 0 0 20px var(--primary-color);
            }

            .close-modal {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .close-modal:hover {
                color: var(--primary-color);
            }

            .modal-title {
                color: var(--secondary-color);
                margin-bottom: 20px;
                font-size: 1.5rem;
                text-align: center;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-group label {
                display: block;
                margin-bottom: 5px;
                color: var(--primary-color);
            }

            .form-group input,
            .form-group select {
                width: 100%;
                padding: 10px;
                background: var(--dark-bg);
                border: 1px solid var(--primary-color);
                border-radius: 5px;
                color: white;
                font-family: 'Courier New', monospace;
            }

            .form-submit {
                width: 100%;
                padding: 12px;
                background: var(--primary-color);
                color: #000;
                border: none;
                border-radius: 5px;
                font-family: 'Courier New', monospace;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s;
                margin-top: 10px;
            }

            .form-submit:hover {
                background: var(--secondary-color);
            }

            /* Avatar Selection */
            .avatar-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                gap: 15px;
                margin: 20px 0;
            }

            .avatar-option {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                border: 2px solid transparent;
                cursor: pointer;
                transition: all 0.3s;
                object-fit: cover;
            }

            .avatar-option:hover {
                border-color: var(--primary-color);
                transform: scale(1.05);
            }

            .avatar-option.selected {
                border-color: var(--accent-color);
                box-shadow: 0 0 10px var(--accent-color);
                transform: scale(1.1);
            }

            /* Missões */
            .quest-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 1px solid rgba(0, 255, 0, 0.2);
            }

            .quest-icon {
                font-size: 1.5rem;
                margin-right: 15px;
                color: var(--accent-color);
            }

            .quest-info {
                flex-grow: 1;
            }

            .quest-title {
                font-weight: bold;
                color: var(--secondary-color);
            }

            .quest-description {
                font-size: 0.9rem;
                color: #ccc;
            }

            .quest-reward {
                display: flex;
                align-items: center;
                font-size: 0.9rem;
                color: var(--coin-color);
            }

            .quest-reward i {
                margin-right: 5px;
            }

            .quest-progress {
                width: 100%;
                height: 5px;
                background: var(--dark-bg);
                border-radius: 5px;
                margin-top: 5px;
                overflow: hidden;
            }

            .progress-bar {
                height: 100%;
                background: var(--primary-color);
                border-radius: 5px;
                transition: width 0.3s;
            }

            /* Botão Home */
            .home-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                background: var(--primary-color);
                color: #000;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                text-decoration: none;
                box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
                z-index: 100;
                transition: all 0.3s;
            }

            .home-button:hover {
                transform: scale(1.1);
                background: var(--secondary-color);
            }

            /* Configurações */
            .settings-item {
                display: flex;
                align-items: center;
                padding: 15px;
                border-bottom: 1px solid rgba(0, 255, 0, 0.2);
                cursor: pointer;
                transition: all 0.3s;
            }

            .settings-item:hover {
                background: rgba(0, 255, 0, 0.1);
            }

            .settings-icon {
                font-size: 1.5rem;
                margin-right: 15px;
                color: var(--accent-color);
                width: 30px;
                text-align: center;
            }

            .settings-text {
                flex-grow: 1;
            }

            .settings-title {
                font-weight: bold;
                color: var(--secondary-color);
            }

            .settings-description {
                font-size: 0.8rem;
                color: #ccc;
            }

            /* Loja */
            .shop-search {
                display: flex;
                margin-bottom: 20px;
                gap: 10px;
            }

            .shop-search input {
                flex-grow: 1;
                padding: 10px;
                background: var(--dark-bg);
                border: 1px solid var(--primary-color);
                border-radius: 5px;
                color: white;
                font-family: 'Courier New', monospace;
            }

            .shop-filters {
                display: flex;
                gap: 10px;
                margin-bottom: 20px;
                flex-wrap: wrap;
            }

            .filter-button {
                padding: 8px 15px;
                background: var(--dark-bg);
                border: 1px solid var(--primary-color);
                border-radius: 20px;
                color: var(--primary-color);
                cursor: pointer;
                transition: all 0.3s;
            }

            .filter-button:hover,
            .filter-button.active {
                background: var(--primary-color);
                color: #000;
            }

            .shop-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }

            .shop-item {
                background: var(--card-bg);
                border: 2px solid var(--primary-color);
                border-radius: 10px;
                overflow: hidden;
                transition: all 0.3s;
            }

            .shop-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 255, 0, 0.2);
            }

            .shop-item-image {
                width: 100%;
                height: 150px;
                object-fit: cover;
            }

            .shop-item-details {
                padding: 15px;
            }

            .shop-item-title {
                font-weight: bold;
                color: var(--secondary-color);
                margin-bottom: 5px;
            }

            .shop-item-description {
                font-size: 0.8rem;
                color: #ccc;
                margin-bottom: 10px;
                height: 40px;
                overflow: hidden;
            }

            .shop-item-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .shop-item-price {
                color: var(--coin-color);
                font-weight: bold;
                display: flex;
                align-items: center;
            }

            .shop-item-price i {
                margin-right: 5px;
            }

            .buy-button {
                padding: 5px 10px;
                background: var(--coin-color);
                color: #000;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s;
            }

            .buy-button:hover {
                background: #ffcc00;
            }

            .buy-button.owned {
                background: var(--primary-color);
            }

            /* Raridades */
            .common {
                border-color: var(--primary-color);
            }

            .rare {
                border-color: #0099ff;
                box-shadow: 0 0 10px #0099ff;
            }

            .epic {
                border-color: #b36bff;
                box-shadow: 0 0 10px #b36bff;
            }

            .legendary {
                border-color: transparent;
                background-image: var(--legendary-gradient);
                background-origin: border-box;
                background-clip: padding-box, border-box;
                position: relative;
            }

            .legendary::before {
                content: '';
                position: absolute;
                top: -2px;
                left: -2px;
                right: -2px;
                bottom: -2px;
                background: var(--legendary-gradient);
                border-radius: 10px;
                z-index: -1;
                animation: legendaryGlow 2s infinite alternate;
            }

            @keyframes legendaryGlow {
                0% {
                    opacity: 0.7;
                }

                100% {
                    opacity: 1;
                }
            }

            /* Notification */
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--darker-bg);
                border: 2px solid var(--primary-color);
                border-radius: 5px;
                padding: 15px;
                max-width: 300px;
                transform: translateX(150%);
                transition: transform 0.3s;
                z-index: 1000;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            }

            .notification.show {
                transform: translateX(0);
            }

            .notification-title {
                color: var(--secondary-color);
                font-weight: bold;
                margin-bottom: 5px;
            }

            .notification-message {
                color: var(--primary-color);
                font-size: 0.9rem;
            }

            /* Responsive */
            @media (max-width: 1024px) {
                .main-container {
                    flex-direction: column;
                }
            }

            @media (max-width: 768px) {
                .header {
                    flex-direction: column;
                    gap: 15px;
                    padding: 15px;
                }

                .logo-container {
                    position: static;
                    transform: none;
                    order: -1;
                    margin-bottom: 10px;
                }

                .auth-section {
                    width: 100%;
                    justify-content: center;
                }

                .shop-grid {
                    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                }
            }

            /* NOVOS ESTILOS ADICIONADOS */
            /* Estilo para o modal de denúncia */
            .report-reason {
                margin: 10px 0;
                padding: 10px;
                border: 1px solid var(--primary-color);
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s;
            }

            .report-reason:hover {
                background: rgba(0, 255, 0, 0.1);
            }

            .report-reason.selected {
                background: var(--primary-color);
                color: black;
            }

            #report-comment {
                width: 100%;
                height: 100px;
                padding: 10px;
                margin-top: 10px;
                background: var(--dark-bg);
                border: 1px solid var(--primary-color);
                color: white;
                display: none;
            }

            /* Estilo para jogos em construção */
            .coming-soon {
                position: absolute;
                top: 10px;
                right: 10px;
                background: var(--accent-color);
                color: black;
                padding: 5px 10px;
                border-radius: 5px;
                font-weight: bold;
                font-size: 0.8rem;
            }

            /* Botão de demonstração */
            #demo-level-up {
                display: none;
                position: fixed;
                bottom: 100px;
                right: 20px;
                z-index: 1000;
                padding: 10px 15px;
                background: var(--accent-color);
                color: black;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                cursor: pointer;
                box-shadow: 0 0 10px var(--accent-color);
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <header class="header">
            <div class="auth-section" id="auth-section">
                <button class="auth-button" id="login-btn">ENTRAR</button>
                <button class="auth-button" id="register-btn">REGISTRAR</button>
            </div>

            <div class="logo-container">
                <div class="logo">CHEF GAME ZONE</div>
                <div class="logo-slogan">Onde a diversão e a culinária se encontram!</div>
            </div>

            <div class="user-profile" id="user-profile" style="display: none;">
                <div class="user-avatar" id="user-avatar">
                    <div class="user-level-badge" id="user-level-badge"></div>
                </div>
                <div class="user-info">
                    <span class="user-name" id="user-name"></span>
                    <div class="user-coins">
                        <i class="fas fa-coins"></i>
                        <span id="user-coins-amount">0</span>
                    </div>
                </div>
                <button class="auth-button" id="logout-btn">SAIR</button>
            </div>
        </header>

        <?php if ($user) { ?>


        <?php } else {
            header("Location: login.php");
            exit;
        } ?>

        <!-- Main Content -->
        <main class="main-container">
            <section class="games-section">
                <h2 class="section-title">NOSSOS JOGOS</h2>

                <div class="games-grid">
                    <!-- Game 1 -->
                    <div class="game-card">
                        <img src="imgf/jogogarçomcorredor.png" alt="Garçom Corredor" class="game-image">

                        <div class="game-overlay">
                            <h3 class="game-title">GARÇOM CORREDOR</h3>
                            <p class="game-description">Colete todas as comidas antes que os garçons te peguem! Um jogo de ação e estratégia cheio de desafios.</p>

                            <div class="game-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-value">4.7 (1,234 avaliações)</span>
                            </div>

                            <a href="pou-comilao.php" class="play-button pulse">JOGAR AGORA</a>
                        </div>
                    </div>

                    <!-- Game 2 -->
                    <div class="game-card">
                        <img src="imgf/chefpuzzle.png" alt="Chef Puzzle" class="game-image">

                        <div class="game-overlay">
                            <h3 class="game-title">CHEF PUZZLE</h3>
                            <p class="game-description">Monte pratos deliciosos resolvendo quebra-cabeças desafiadores. Cada nível traz ingredientes novos!</p>

                            <div class="game-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="rating-value">4.2 (987 avaliações)</span>
                            </div>

                            <a href="pac-man.php" class="play-button">JOGAR AGORA</a>
                        </div>
                    </div>

                    <!-- Game 3 -->
                    <div class="game-card">
                        <img src="imgf/restaurante-rush.jpg" alt="Restaurante Rush" class="game-image">

                        <div class="game-overlay">
                            <h3 class="game-title">RESTAURANTE RUSH</h3>
                            <p class="game-description">Gerencie seu próprio restaurante, contrate funcionários e sirva clientes exigentes no menor tempo possível!</p>

                            <div class="game-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="rating-value">5.0 (2,345 avaliações)</span>
                            </div>

                            <a href="#" class="play-button">EM BREVE</a>
                        </div>
                    </div>

                    <!-- Novos jogos em construção -->
                    <div class="game-card">
                        <img src="imgf/coming-soon1.jpg" alt="Jogo em Construção" class="game-image">
                        <div class="coming-soon">EM BREVE</div>
                        <div class="game-overlay">
                            <h3 class="game-title">CHEF RACER</h3>
                            <p class="game-description">Corrida emocionante com chefs competindo em pistas de cozinha!</p>
                            <div class="game-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="rating-value">- (Em breve)</span>
                            </div>
                            <a href="#" class="play-button">EM BREVE</a>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="imgf/coming-soon2.jpg" alt="Jogo em Construção" class="game-image">
                        <div class="coming-soon">EM CONSTRUÇÃO</div>
                        <div class="game-overlay">
                            <h3 class="game-title">INGREDIENTE HUNTER</h3>
                            <p class="game-description">Aventura para coletar ingredientes raros em florestas exóticas!</p>
                            <div class="game-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="rating-value">- (Em breve)</span>
                            </div>
                            <a href="#" class="play-button">EM BREVE</a>
                        </div>
                    </div>

                    <div class="game-card">
                        <img src="imgf/coming-soon3.jpg" alt="Jogo em Construção" class="game-image">
                        <div class="coming-soon">EM DESENVOLVIMENTO</div>
                        <div class="game-overlay">
                            <h3 class="game-title">RESTAURANT WARS</h3>
                            <p class="game-description">Batalha entre restaurantes para ver quem domina a cidade!</p>
                            <div class="game-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="rating-value">- (Em breve)</span>
                            </div>
                            <a href="#" class="play-button">EM BREVE</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Botões de Ação -->
                <a href="#" class="action-button shop-button" id="shop-btn">
                    <i class="fas fa-store"></i> LOJA
                </a>

                <a href="#" class="action-button settings-button" id="settings-btn">
                    <i class="fas fa-cog"></i> CONFIGURAÇÕES
                </a>

                <!-- User Stats -->
                <div class="sidebar-card">
                    <h3 class="card-title">SEU PROGRESSO</h3>

                    <div class="user-stats" id="user-stats">
                        <div class="stat-item">
                            <span class="stat-label">Nível:</span>
                            <span class="stat-value" id="stat-level">1</span>
                        </div>

                        <div class="stat-item">
                            <span class="stat-label">Moedas:</span>
                            <span class="stat-value" id="stat-coins">0</span>
                        </div>

                        <div class="stat-item">
                            <span class="stat-label">Próxima Recompensa:</span>
                            <span class="stat-value" id="stat-next-reward">Nível 3</span>
                        </div>
                    </div>
                </div>

                <!-- Missões Atualizadas -->
                <div class="sidebar-card">
                    <h3 class="card-title">SUAS MISSÕES</h3>

                    <div class="quests-list" id="quests-list">
                        <div class="quest-item">
                            <i class="fas fa-gamepad quest-icon"></i>
                            <div class="quest-info">
                                <div class="quest-title">Primeira Jogatina</div>
                                <div class="quest-description">Jogue pela primeira vez</div>
                                <div class="quest-progress">
                                    <div class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="quest-reward">
                                <i class="fas fa-coins"></i> 50
                            </div>
                        </div>

                        <div class="quest-item">
                            <i class="fas fa-play-circle quest-icon"></i>
                            <div class="quest-info">
                                <div class="quest-title">Viciado em Jogos</div>
                                <div class="quest-description">Jogue 3 vezes qualquer jogo</div>
                                <div class="quest-progress">
                                    <div class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="quest-reward">
                                <i class="fas fa-coins"></i> 100
                            </div>
                        </div>

                        <div class="quest-item">
                            <i class="fas fa-star quest-icon"></i>
                            <div class="quest-info">
                                <div class="quest-title">Crítico de Jogos</div>
                                <div class="quest-description">Avalie um jogo</div>
                                <div class="quest-progress">
                                    <div class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="quest-reward">
                                <i class="fas fa-coins"></i> 80
                            </div>
                        </div>

                        <div class="quest-item">
                            <i class="fas fa-globe quest-icon"></i>
                            <div class="quest-info">
                                <div class="quest-title">Explorador</div>
                                <div class="quest-description">Jogue todos os jogos disponíveis</div>
                                <div class="quest-progress">
                                    <div class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="quest-reward">
                                <i class="fas fa-coins"></i> 200
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itens Comprados -->
                <div class="sidebar-card">
                    <h3 class="card-title">SEUS ITENS</h3>

                    <div class="inventory-list" id="inventory-list">
                        <p style="color: #ccc; text-align: center;">Nenhum item comprado ainda</p>
                    </div>
                </div>
            </aside>
        </main>

        <!-- Botão Home -->
        <a href="index.php" class="home-button" title="Voltar para página inicial">
            <i class="fas fa-home"></i>
        </a>

        <!-- Modal de Login -->
        <div id="login-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 class="modal-title">ENTRAR</h2>
                <form id="login-form">
                    <div class="form-group">
                        <label for="login-email">E-mail:</label>
                        <input type="email" id="login-email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Senha:</label>
                        <input type="password" id="login-password" required>
                    </div>
                    <button type="submit" class="form-submit">ENTRAR</button>
                </form>
            </div>
        </div>

        <!-- Modal de Registro -->
        <div id="register-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 class="modal-title">REGISTRAR</h2>
                <form id="register-form">
                    <div class="form-group">
                        <label for="register-name">Nome:</label>
                        <input type="text" id="register-name" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">E-mail:</label>
                        <input type="email" id="register-email" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Senha:</label>
                        <input type="password" id="register-password" required>
                    </div>
                    <div class="form-group">
                        <label for="register-confirm-password">Confirmar Senha:</label>
                        <input type="password" id="register-confirm-password" required>
                    </div>

                    <div class="form-group">
                        <label>Escolha seu avatar:</label>
                        <div class="avatar-grid" id="avatar-grid">
                            <!-- Avatares serão adicionados via JavaScript -->
                        </div>
                    </div>

                    <button type="submit" class="form-submit">REGISTRAR</button>
                </form>
            </div>
        </div>

        <!-- Modal de Configurações -->
        <div id="settings-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 class="modal-title">CONFIGURAÇÕES</h2>

                <div class="settings-item" id="help-btn">
                    <div class="settings-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Ajuda</div>
                        <div class="settings-description">Tutoriais e perguntas frequentes</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item" id="report-btn">
                    <div class="settings-icon">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Denunciar Problema</div>
                        <div class="settings-description">Reportar bugs ou comportamentos inadequados</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item" id="notifications-btn">
                    <div class="settings-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Notificações</div>
                        <div class="settings-description">Configurar alertas e notificações</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item" id="privacy-btn">
                    <div class="settings-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Privacidade</div>
                        <div class="settings-description">Configurações de privacidade e segurança</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item" id="theme-btn">
                    <div class="settings-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Tema</div>
                        <div class="settings-description">Alterar aparência do aplicativo</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item" id="about-btn">
                    <div class="settings-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Sobre</div>
                        <div class="settings-description">Informações sobre o aplicativo</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>

        <!-- Modal de Loja -->
        <div id="shop-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 class="modal-title">LOJA DE ITENS</h2>

                <div class="shop-search">
                    <input type="text" id="shop-search" placeholder="Pesquisar itens...">
                    <button class="filter-button active">TODOS</button>
                </div>

                <div class="shop-filters">
                    <button class="filter-button" data-filter="all">TODOS</button>
                    <button class="filter-button" data-filter="avatar">AVATARES</button>
                    <button class="filter-button" data-filter="frame">MOLDURAS</button>
                    <button class="filter-button" data-filter="title">TÍTULOS</button>
                    <button class="filter-button" data-filter="common">COMUNS</button>
                    <button class="filter-button" data-filter="rare">RAROS</button>
                    <button class="filter-button" data-filter="epic">ÉPICOS</button>
                    <button class="filter-button" data-filter="legendary">LENDÁRIOS</button>
                </div>

                <div class="shop-grid" id="shop-grid">
                    <!-- Itens da loja serão adicionados via JavaScript -->
                </div>
            </div>
        </div>

        <!-- Modal de Ajuda -->
        <div id="help-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 class="modal-title">AJUDA E SUPORTE</h2>

                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Como Jogar</div>
                        <div class="settings-description">Tutoriais e guias para todos os jogos</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Sistema de Moedas</div>
                        <div class="settings-description">Como ganhar e gastar suas moedas</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Missões e Recompensas</div>
                        <div class="settings-description">Como completar missões e ganhar prêmios</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="fas fa-question"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Perguntas Frequentes</div>
                        <div class="settings-description">Respostas para as dúvidas mais comuns</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="settings-item">
                    <div class="settings-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="settings-text">
                        <div class="settings-title">Contate-nos</div>
                        <div class="settings-description">Entre em contato com nosso suporte</div>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>

        <!-- Modal de Denúncia Detalhado -->
        <div id="report-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2 class="modal-title">DENUNCIAR PROBLEMA</h2>

                <div class="form-group">
                    <label>Selecione o jogo:</label>
                    <select id="report-game" class="form-group">
                        <option value="garcom-corredor">Garçom Corredor</option>
                        <option value="chef-puzzle">Chef Puzzle</option>
                        <option value="restaurante-rush">Restaurante Rush</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Motivo da denúncia:</label>
                    <div class="report-reason" data-reason="bug">Bug ou problema técnico</div>
                    <div class="report-reason" data-reason="inappropriate">Conteúdo inapropriado</div>
                    <div class="report-reason" data-reason="cheat">Trapaça/hack</div>
                    <div class="report-reason" data-reason="other">Outro motivo</div>
                    <textarea id="report-comment" placeholder="Por favor, descreva o problema..."></textarea>
                </div>

                <button id="submit-report" class="form-submit">ENVIAR DENÚNCIA</button>
            </div>
        </div>

        <!-- Notification -->
        <div class="notification" id="notification">
            <div class="notification-title" id="notification-title"></div>
            <div class="notification-message" id="notification-message"></div>
        </div>

        <!-- Botão de Demonstração -->
        <button id="demo-level-up">
            <i class="fas fa-arrow-up"></i> UPAR LEVEL (DEMO)
        </button>

        <script>
            // Dados do usuário
            let currentUser = null;
            let users = JSON.parse(localStorage.getItem('chefGameUsers')) || [];

            // Avatares disponíveis (você pode substituir por suas próprias imagens)
            const availableAvatars = [
                'imgf/avatar1.png',
                'img/avatar2.png',
                'img/avatar3.png',
                'img/avatar4.png',
                'img/avatar5.png',
                'img/avatar6.png'
            ];

            // Itens da loja
            const shopItems = [{
                    id: 1,
                    name: "Avatar de Chef Clássico",
                    description: "Um avatar elegante para seu perfil",
                    price: 200,
                    type: "avatar",
                    rarity: "common",
                    image: "img/shop-avatar1.png"
                },
                {
                    id: 2,
                    name: "Moldura Dourada",
                    description: "Destaque seu perfil com esta moldura",
                    price: 500,
                    type: "frame",
                    rarity: "rare",
                    image: "img/shop-frame1.png"
                },
                {
                    id: 3,
                    name: "Título 'Mestre Culinário'",
                    description: "Exiba seu título exclusivo",
                    price: 800,
                    type: "title",
                    rarity: "epic",
                    image: "img/shop-title1.png"
                },
                {
                    id: 4,
                    name: "Avatar Lendário",
                    description: "Um avatar exclusivo para verdadeiros chefs",
                    price: 1500,
                    type: "avatar",
                    rarity: "legendary",
                    image: "img/shop-avatar-legendary.png"
                },
                {
                    id: 5,
                    name: "Moldura de Diamante",
                    description: "A moldura mais cobiçada da plataforma",
                    price: 2000,
                    type: "frame",
                    rarity: "legendary",
                    image: "img/shop-frame-legendary.png"
                },
                {
                    id: 6,
                    name: "Título 'Lenda da Cozinha'",
                    description: "O título mais exclusivo de todos",
                    price: 3000,
                    type: "title",
                    rarity: "legendary",
                    image: "img/shop-title-legendary.png"
                },
                {
                    id: 7,
                    name: "Avatar Festivo",
                    description: "Celebre com este avatar especial",
                    price: 400,
                    type: "avatar",
                    rarity: "rare",
                    image: "img/avatarcomemorativo.png"
                },
                {
                    id: 8,
                    name: "Moldura Neon",
                    description: "Brilhe com esta moldura vibrante",
                    price: 600,
                    type: "frame",
                    rarity: "epic",
                    image: "img/shop-frame2.png"
                }
            ];

            // Recompensas por level
            const levelRewards = {
                3: {
                    title: "Chefe Novato",
                    description: "100 moedas de bônus",
                    reward: 100,
                    icon: "fa-user-chef"
                },
                5: {
                    title: "Cozinheiro Talentoso",
                    description: "300 moedas de bônus",
                    reward: 300,
                    icon: "fa-award"
                },
                7: {
                    title: "Mestre Culinário",
                    description: "500 moedas de bônus",
                    reward: 500,
                    icon: "fa-crown"
                },
                10: {
                    title: "Lenda da Cozinha",
                    description: "1000 moedas de bônus",
                    reward: 1000,
                    icon: "fa-trophy"
                }
            };

            // Missões Atualizadas
            const quests = [{
                    id: 1,
                    title: "Primeira Jogatina",
                    description: "Jogue pela primeira vez",
                    target: 1,
                    current: 0,
                    reward: 50,
                    icon: "fa-gamepad"
                },
                {
                    id: 2,
                    title: "Viciado em Jogos",
                    description: "Jogue 3 vezes qualquer jogo",
                    target: 3,
                    current: 0,
                    reward: 100,
                    icon: "fa-play-circle"
                },
                {
                    id: 3,
                    title: "Crítico de Jogos",
                    description: "Avalie um jogo",
                    target: 1,
                    current: 0,
                    reward: 80,
                    icon: "fa-star"
                },
                {
                    id: 4,
                    title: "Explorador",
                    description: "Jogue todos os jogos disponíveis",
                    target: 2, // Atualize conforme número de jogos
                    current: 0,
                    reward: 200,
                    icon: "fa-globe"
                },
                {
                    id: 5,
                    title: "Conquistador",
                    description: "Alcance o nível 5",
                    target: 5,
                    current: 1,
                    reward: 300,
                    icon: "fa-medal"
                }
            ];

            // Elementos DOM
            const authSection = document.getElementById('auth-section');
            const userProfile = document.getElementById('user-profile');
            const userAvatar = document.getElementById('user-avatar');
            const userName = document.getElementById('user-name');
            const userCoins = document.getElementById('user-coins-amount');
            const userLevelBadge = document.getElementById('user-level-badge');
            const statLevel = document.getElementById('stat-level');
            const statCoins = document.getElementById('stat-coins');
            const statNextReward = document.getElementById('stat-next-reward');
            const rewardsList = document.getElementById('rewards-list');
            const questsList = document.getElementById('quests-list');
            const inventoryList = document.getElementById('inventory-list');
            const shopBtn = document.getElementById('shop-btn');
            const settingsBtn = document.getElementById('settings-btn');
            const notification = document.getElementById('notification');
            const notificationTitle = document.getElementById('notification-title');
            const notificationMessage = document.getElementById('notification-message');
            const loginBtn = document.getElementById('login-btn');
            const registerBtn = document.getElementById('register-btn');
            const logoutBtn = document.getElementById('logout-btn');
            const loginModal = document.getElementById('login-modal');
            const registerModal = document.getElementById('register-modal');
            const settingsModal = document.getElementById('settings-modal');
            const shopModal = document.getElementById('shop-modal');
            const helpModal = document.getElementById('help-modal');
            const reportModal = document.getElementById('report-modal');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const avatarGrid = document.getElementById('avatar-grid');
            const shopGrid = document.getElementById('shop-grid');
            const shopSearch = document.getElementById('shop-search');
            const filterButtons = document.querySelectorAll('.filter-button');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const helpBtn = document.getElementById('help-btn');
            const reportBtn = document.getElementById('report-btn');
            const submitReportBtn = document.getElementById('submit-report');
            const demoLevelUpBtn = document.getElementById('demo-level-up');

            // Mostrar modal
            function showModal(modal) {
                modal.style.display = 'block';

                // Carregar conteúdo específico se necessário
                if (modal === registerModal) {
                    loadAvatarSelection();
                } else if (modal === shopModal) {
                    loadShopItems();
                }
            }

            // Fechar modal
            function closeModal(modal) {
                modal.style.display = 'none';
            }

            // Mostrar notificação
            function showNotification(title, message, duration = 3000) {
                notificationTitle.textContent = title;
                notificationMessage.textContent = message;
                notification.classList.add('show');

                setTimeout(() => {
                    notification.classList.remove('show');
                }, duration);
            }

            // Carregar seleção de avatares
            function loadAvatarSelection() {
                avatarGrid.innerHTML = '';

                availableAvatars.forEach((avatar, index) => {
                    const avatarOption = document.createElement('img');
                    avatarOption.src = avatar;
                    avatarOption.alt = `Avatar ${index + 1}`;
                    avatarOption.className = 'avatar-option';
                    avatarOption.dataset.avatar = avatar;

                    avatarOption.addEventListener('click', function() {
                        document.querySelectorAll('.avatar-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        this.classList.add('selected');
                    });

                    // Selecionar o primeiro avatar por padrão
                    if (index === 0) {
                        avatarOption.classList.add('selected');
                    }

                    avatarGrid.appendChild(avatarOption);
                });
            }

            // Carregar itens da loja
            function loadShopItems(filter = 'all', searchTerm = '') {
                shopGrid.innerHTML = '';

                const filteredItems = shopItems.filter(item => {
                    const matchesFilter = filter === 'all' || item.type === filter || item.rarity === filter;
                    const matchesSearch = item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        item.description.toLowerCase().includes(searchTerm.toLowerCase());
                    return matchesFilter && matchesSearch;
                });

                if (filteredItems.length === 0) {
                    shopGrid.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: #ccc;">Nenhum item encontrado</p>';
                    return;
                }

                filteredItems.forEach(item => {
                    const isOwned = currentUser && currentUser.inventory && currentUser.inventory.some(owned => owned.id === item.id);

                    const shopItem = document.createElement('div');
                    shopItem.className = `shop-item ${item.rarity}`;
                    shopItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" class="shop-item-image">
                    <div class="shop-item-details">
                        <div class="shop-item-title">${item.name}</div>
                        <div class="shop-item-description">${item.description}</div>
                        <div class="shop-item-footer">
                            <div class="shop-item-price">
                                <i class="fas fa-coins"></i> ${item.price}
                            </div>
                            <button class="buy-button ${isOwned ? 'owned' : ''}" data-id="${item.id}">
                                ${isOwned ? 'COMPRADO' : 'COMPRAR'}
                            </button>
                        </div>
                    </div>
                `;

                    shopGrid.appendChild(shopItem);
                });

                // Adicionar event listeners aos botões de compra
                document.querySelectorAll('.buy-button:not(.owned)').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const itemId = parseInt(this.dataset.id);
                        buyShopItem(itemId);
                    });
                });
            }

            // Comprar item da loja
            function buyShopItem(itemId) {
                if (!currentUser) {
                    showNotification('Erro', 'Você precisa estar logado para comprar itens!');
                    return;
                }

                const item = shopItems.find(i => i.id === itemId);

                if (!item) {
                    showNotification('Erro', 'Item não encontrado!');
                    return;
                }

                if (currentUser.coins < item.price) {
                    showNotification('Erro', 'Moedas insuficientes para comprar este item!');
                    return;
                }

                // Verificar se o usuário já possui o item
                if (currentUser.inventory.some(owned => owned.id === item.id)) {
                    showNotification('Aviso', 'Você já possui este item!');
                    return;
                }

                // Comprar item
                currentUser.coins -= item.price;
                currentUser.inventory.push(item);

                // Atualizar missão "Colecionador"
                const collectorQuest = currentUser.quests.find(q => q.id === 4);
                if (collectorQuest) {
                    collectorQuest.current += 1;

                    if (collectorQuest.current === collectorQuest.target) {
                        currentUser.coins += collectorQuest.reward;
                        showNotification('Missão Completa!', `Você completou "${collectorQuest.title}" e ganhou ${collectorQuest.reward} moedas!`);
                    }
                }

                updateUserInDatabase();
                updateUserProfile();
                loadShopItems();
                showNotification('Sucesso!', `Você comprou ${item.name} por ${item.price} moedas!`);
            }

            // Atualizar perfil do usuário
            function updateUserProfile() {
                if (currentUser) {
                    authSection.style.display = 'none';
                    userProfile.style.display = 'flex';

                    userName.textContent = currentUser.name;
                    userCoins.textContent = currentUser.coins;
                    userLevelBadge.textContent = currentUser.level;
                    userAvatar.style.backgroundImage = `url('${currentUser.avatar}')`;

                    // Atualizar estatísticas
                    statLevel.textContent = currentUser.level;
                    statCoins.textContent = currentUser.coins;

                    // Encontrar próxima recompensa
                    let nextRewardLevel = null;
                    for (let level in levelRewards) {
                        if (currentUser.level < parseInt(level)) {
                            nextRewardLevel = parseInt(level);
                            break;
                        }
                    }

                    statNextReward.textContent = nextRewardLevel ? `Nível ${nextRewardLevel}` : "Máximo alcançado!";

                    // Atualizar lista de recompensas e missões
                    updateRewardsList();
                    updateQuestsList();
                    updateInventoryList();

                    // Mostrar botão de demonstração se usuário estiver logado
                    demoLevelUpBtn.style.display = 'block';
                } else {
                    authSection.style.display = 'flex';
                    userProfile.style.display = 'none';
                    demoLevelUpBtn.style.display = 'none';
                }
            }

            // Atualizar lista de recompensas
            function updateRewardsList() {
                rewardsList.innerHTML = '';

                for (const [level, reward] of Object.entries(levelRewards)) {
                    const rewardItem = document.createElement('div');
                    rewardItem.className = 'reward-item';

                    if (currentUser.level >= parseInt(level)) {
                        rewardItem.innerHTML = `
                        <i class="fas ${reward.icon} reward-icon" style="color: var(--accent-color)"></i>
                        <div class="reward-info">
                            <div class="reward-title">${reward.title} (Nível ${level})</div>
                            <div class="reward-description">${reward.description}</div>
                        </div>
                        <i class="fas fa-check" style="color: var(--primary-color)"></i>
                    `;
                    } else {
                        rewardItem.innerHTML = `
                        <i class="fas fa-lock reward-icon"></i>
                        <div class="reward-info">
                            <div class="reward-title">${reward.title} (Nível ${level})</div>
                            <div class="reward-description">${reward.description}</div>
                        </div>
                    `;
                    }

                    rewardsList.appendChild(rewardItem);
                }
            }

            // Atualizar lista de missões
            function updateQuestsList() {
                questsList.innerHTML = '';

                currentUser.quests.forEach(quest => {
                    const progress = (quest.current / quest.target) * 100;

                    const questItem = document.createElement('div');
                    questItem.className = 'quest-item';
                    questItem.innerHTML = `
                    <i class="fas ${quest.icon} quest-icon"></i>
                    <div class="quest-info">
                        <div class="quest-title">${quest.title}</div>
                        <div class="quest-description">${quest.description}</div>
                        <div class="quest-progress">
                            <div class="progress-bar" style="width: ${progress}%"></div>
                        </div>
                    </div>
                    <div class="quest-reward">
                        <i class="fas fa-coins"></i> ${quest.reward}
                    </div>
                `;

                    questsList.appendChild(questItem);
                });
            }

            // Atualizar lista de inventário
            function updateInventoryList() {
                inventoryList.innerHTML = '';

                if (!currentUser.inventory || currentUser.inventory.length === 0) {
                    inventoryList.innerHTML = '<p style="color: #ccc; text-align: center;">Nenhum item comprado ainda</p>';
                    return;
                }

                currentUser.inventory.forEach(item => {
                    const inventoryItem = document.createElement('div');
                    inventoryItem.className = 'quest-item';
                    inventoryItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover; margin-right: 15px;">
                    <div class="quest-info">
                        <div class="quest-title">${item.name}</div>
                        <div class="quest-description">${item.description}</div>
                    </div>
                    <div class="quest-reward">
                        <span class="${item.rarity}" style="font-size: 0.8rem;">
                            ${item.rarity.toUpperCase()}
                        </span>
                    </div>
                `;

                    inventoryList.appendChild(inventoryItem);
                });
            }

            // Sistema de recompensa a cada 10 níveis
            function checkLevelRewards() {
                if (currentUser.level % 10 === 0) {
                    const goldReward = 10;
                    currentUser.coins += goldReward;
                    showNotification('Recompensa Especial', `Parabéns! Você ganhou ${goldReward} moedas por alcançar o nível ${currentUser.level}!`);
                    updateUserProfile();
                }
            }

            // Upar de nível
            function levelUp() {
                if (!currentUser) {
                    showNotification('Erro', 'Você precisa estar logado para upar de nível!');
                    return;
                }

                currentUser.xp += 50;

                // Verificar se subiu de nível
                if (currentUser.xp >= currentUser.xpNeeded) {
                    const oldLevel = currentUser.level;
                    currentUser.level += 1;
                    currentUser.xp = currentUser.xp - currentUser.xpNeeded;
                    currentUser.xpNeeded = Math.floor(currentUser.xpNeeded * 1.3);

                    // Verificar se desbloqueou alguma recompensa
                    if (levelRewards[currentUser.level]) {
                        const reward = levelRewards[currentUser.level];
                        currentUser.coins += reward.reward;
                        showNotification('Recompensa Desbloqueada!', `Você ganhou ${reward.reward} moedas por alcançar o nível ${currentUser.level}!`);
                    }

                    // Verificar recompensa a cada 10 níveis
                    checkLevelRewards();

                    showNotification('Level Up!', `Você alcançou o nível ${currentUser.level}!`);

                    // Atualizar missão "Conquistador"
                    const conquerorQuest = currentUser.quests.find(q => q.id === 5);
                    if (conquerorQuest && currentUser.level >= conquerorQuest.target) {
                        conquerorQuest.current = conquerorQuest.target;
                        currentUser.coins += conquerorQuest.reward;
                        showNotification('Missão Completa!', `Você completou "${conquerorQuest.title}" e ganhou ${conquerorQuest.reward} moedas!`);
                    }

                    // Atualizar usuário no banco de dados
                    updateUserInDatabase();
                } else {
                    showNotification('XP Adicionado!', `Você ganhou 50 XP!`);
                }

                updateUserProfile();
            }

            // Atualizar missões relacionadas a jogos
            function updateGameQuests(gameId) {
                if (!currentUser) return;

                // Missão "Primeira Jogatina"
                const firstPlayQuest = currentUser.quests.find(q => q.id === 1);
                if (firstPlayQuest && firstPlayQuest.current < firstPlayQuest.target) {
                    firstPlayQuest.current += 1;
                    if (firstPlayQuest.current === firstPlayQuest.target) {
                        currentUser.coins += firstPlayQuest.reward;
                        showNotification('Missão Completa!', `Você completou "${firstPlayQuest.title}" e ganhou ${firstPlayQuest.reward} moedas!`);
                    }
                }

                // Missão "Viciado em Jogos"
                const playThreeQuest = currentUser.quests.find(q => q.id === 2);
                if (playThreeQuest && playThreeQuest.current < playThreeQuest.target) {
                    playThreeQuest.current += 1;
                    if (playThreeQuest.current === playThreeQuest.target) {
                        currentUser.coins += playThreeQuest.reward;
                        showNotification('Missão Completa!', `Você completou "${playThreeQuest.title}" e ganhou ${playThreeQuest.reward} moedas!`);
                    }
                }

                // Missão "Explorador"
                const exploreQuest = currentUser.quests.find(q => q.id === 4);
                if (exploreQuest) {
                    // Verifica se jogou todos os jogos (implemente sua lógica específica)
                    const uniqueGamesPlayed = new Set(currentUser.gamesPlayed || []);
                    uniqueGamesPlayed.add(gameId);
                    exploreQuest.current = uniqueGamesPlayed.size;

                    if (exploreQuest.current === exploreQuest.target) {
                        currentUser.coins += exploreQuest.reward;
                        showNotification('Missão Completa!', `Você completou "${exploreQuest.title}" e ganhou ${exploreQuest.reward} moedas!`);
                    }
                }

                updateUserInDatabase();
                updateUserProfile();
            }

            // Registrar novo usuário
            function registerUser(name, email, password, avatar) {
                // Verificar se usuário já existe
                const userExists = users.some(user => user.email === email);
                if (userExists) {
                    showNotification('Erro', 'Este e-mail já está cadastrado!');
                    return false;
                }

                // Criar novo usuário
                const newUser = {
                    id: Date.now(),
                    name,
                    email,
                    password,
                    avatar,
                    level: 1,
                    xp: 0,
                    xpNeeded: 100,
                    coins: 100, // Moedas iniciais
                    inventory: [],
                    quests: JSON.parse(JSON.stringify(quests)) // Clonar array de missões
                };

                users.push(newUser);
                localStorage.setItem('chefGameUsers', JSON.stringify(users));

                // Logar o novo usuário
                currentUser = newUser;
                updateUserProfile();

                showNotification('Bem-vindo!', `Cadastro realizado com sucesso, ${name}! Você ganhou 100 moedas iniciais!`);
                return true;
            }

            // Login do usuário
            function loginUser(email, password) {
                const user = users.find(u => u.email === email && u.password === password);

                if (user) {
                    currentUser = user;
                    updateUserProfile();
                    showNotification('Bem-vindo de volta!', `Olá, ${user.name}!`);
                    return true;
                } else {
                    showNotification('Erro', 'E-mail ou senha incorretos!');
                    return false;
                }
            }

            // Logout do usuário
            function logoutUser() {
                currentUser = null;
                updateUserProfile();
                showNotification('Até logo!', 'Você saiu da sua conta.');
            }

            // Atualizar usuário no banco de dados
            function updateUserInDatabase() {
                if (!currentUser) return;

                const userIndex = users.findIndex(u => u.id === currentUser.id);
                if (userIndex !== -1) {
                    users[userIndex] = currentUser;
                    localStorage.setItem('chefGameUsers', JSON.stringify(users));
                }
            }

            // Event Listeners
            loginBtn.addEventListener('click', () => showModal(loginModal));
            registerBtn.addEventListener('click', () => showModal(registerModal));
            logoutBtn.addEventListener('click', logoutUser);
            shopBtn.addEventListener('click', () => showModal(shopModal));
            settingsBtn.addEventListener('click', () => showModal(settingsModal));
            helpBtn.addEventListener('click', () => {
                closeModal(settingsModal);
                showModal(helpModal);
            });
            reportBtn.addEventListener('click', () => {
                closeModal(settingsModal);
                showModal(reportModal);
            });

            // Sistema de denúncia
            document.querySelectorAll('.report-reason').forEach(reason => {
                reason.addEventListener('click', function() {
                    document.querySelectorAll('.report-reason').forEach(r => r.classList.remove('selected'));
                    this.classList.add('selected');

                    if (this.dataset.reason === 'other') {
                        document.getElementById('report-comment').style.display = 'block';
                    } else {
                        document.getElementById('report-comment').style.display = 'none';
                    }
                });
            });

            submitReportBtn.addEventListener('click', function() {
                const game = document.getElementById('report-game').value;
                const reason = document.querySelector('.report-reason.selected')?.dataset.reason;
                const comment = document.getElementById('report-comment').value;

                if (!reason) {
                    showNotification('Erro', 'Selecione um motivo para a denúncia');
                    return;
                }

                if (reason === 'other' && !comment.trim()) {
                    showNotification('Erro', 'Por favor, descreva o problema');
                    return;
                }

                // Aqui você enviaria a denúncia para o servidor
                showNotification('Sucesso', 'Denúncia enviada com sucesso! Obrigado por ajudar a melhorar nossa plataforma.');
                closeModal(reportModal);

                // Resetar formulário
                document.querySelectorAll('.report-reason').forEach(r => r.classList.remove('selected'));
                document.getElementById('report-comment').style.display = 'none';
                document.getElementById('report-comment').value = '';
            });

            closeModalButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    closeModal(modal);
                });
            });

            window.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal')) {
                    closeModal(e.target);
                }
            });

            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = document.getElementById('login-email').value;
                const password = document.getElementById('login-password').value;

                if (loginUser(email, password)) {
                    closeModal(loginModal);
                    loginForm.reset();
                }
            });

            registerForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const name = document.getElementById('register-name').value;
                const email = document.getElementById('register-email').value;
                const password = document.getElementById('register-password').value;
                const confirmPassword = document.getElementById('register-confirm-password').value;
                const selectedAvatar = document.querySelector('.avatar-option.selected');

                if (!selectedAvatar) {
                    showNotification('Erro', 'Por favor, selecione um avatar!');
                    return;
                }

                if (password !== confirmPassword) {
                    showNotification('Erro', 'As senhas não coincidem!');
                    return;
                }

                if (registerUser(name, email, password, selectedAvatar.dataset.avatar)) {
                    closeModal(registerModal);
                    registerForm.reset();
                }
            });

            // Filtros da loja
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.dataset.filter) {
                        filterButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        loadShopItems(this.dataset.filter, shopSearch.value);
                    }
                });
            });

            // Pesquisa na loja
            shopSearch.addEventListener('input', () => {
                const activeFilter = document.querySelector('.filter-button.active');
                loadShopItems(activeFilter ? activeFilter.dataset.filter : 'all', shopSearch.value);
            });

            // Simular progresso nas missões quando jogos são abertos
            document.querySelectorAll('.play-button').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!this.href.includes('#') && currentUser) {
                        // Obter ID do jogo a partir do link
                        const gameId = this.closest('.game-card').querySelector('.game-image').alt.toLowerCase().replace(/\s+/g, '-');

                        // Atualizar missões relacionadas ao jogo
                        updateGameQuests(gameId);
                    }
                });
            });

            // Botão de demonstração
            demoLevelUpBtn.addEventListener('click', function() {
                if (!currentUser) {
                    showNotification('Demonstração', 'Crie ou faça login em uma conta para testar');
                    return;
                }

                // Simular jogatina para completar missões
                updateGameQuests('garcom-corredor');
                updateGameQuests('chef-puzzle');

                // Aumentar level para demonstração
                currentUser.xp = currentUser.xpNeeded - 1;
                levelUp();

                // Adicionar moedas para demonstração
                currentUser.coins += 500;
                updateUserProfile();

                showNotification('Demonstração', 'Missões e level up simulados com sucesso! +500 moedas adicionadas');
            });

            // Inicializar
            updateUserProfile();
        </script>

    <?php } else {
    header("Location: login.php");
    exit;
} ?>
    </body>

    </html>
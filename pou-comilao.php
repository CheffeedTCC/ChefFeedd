<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pou Comilão</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #0f0f1a;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            overflow: hidden;
        }

        .game-header {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            width: 800px;
            align-items: center;
        }

        .game-title {
            font-size: 2.5rem;
            text-shadow: 0 0 10px #00ff00;
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }

        #game-area {
            display: flex;
            justify-content: space-between;
            width: 800px;
        }

        #game-container {
            position: relative;
            width: 600px;
            height: 500px;
            background-color: #000;
            border: 4px solid #00ff00;
            overflow: hidden;
            box-shadow: 0 0 20px #00ff00;
        }

        #ranking-container {
            width: 180px;
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid #00ff00;
            border-radius: 5px;
            padding: 15px;
            color: #00ff00;
            display: flex;
            flex-direction: column;
        }

        #ranking-container h3 {
            margin-top: 0;
            text-align: center;
            border-bottom: 1px solid #00ff00;
            padding-bottom: 10px;
            font-size: 1.3rem;
        }

        #ranking-list {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .ranking-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.9rem;
            padding: 8px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }

        #player {
            position: absolute;
            width: 60px;
            height: 60px;
            background-size: cover;
            bottom: 10px;
            left: 270px;
            z-index: 10;
            transition: left 0.1s ease-out;
        }

        .food {
            position: absolute;
            width: 40px;
            height: 40px;
            background-size: cover;
            z-index: 5;
        }

        .trash {
            position: absolute;
            width: 40px;
            height: 40px;
            background-size: cover;
            z-index: 5;
        }

        .game-stats {
            display: flex;
            justify-content: space-between;
            width: 600px;
            margin-bottom: 10px;
        }

        .stat-box {
            background-color: rgba(0, 0, 0, 0.7);
            border: 2px solid #00ff00;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 1.1rem;
        }

        #start-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            z-index: 20;
            text-align: center;
        }

        #start-screen h2 {
            font-size: 2.5rem;
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
            margin-bottom: 20px;
        }

        #start-button {
            margin-top: 30px;
            padding: 15px 30px;
            font-size: 1.2rem;
            background-color: #00ff00;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            transition: all 0.3s;
        }

        #start-button:hover {
            background-color: #00cc00;
            transform: scale(1.05);
            box-shadow: 0 0 10px #00ff00;
        }

        #game-over {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            z-index: 30;
            text-align: center;
        }

        #game-over h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
        }

        .result-stats {
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #00ff00;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .game-button {
            padding: 12px 25px;
            background-color: #00ff00;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1rem;
            margin: 5px;
            transition: all 0.3s;
        }

        .game-button:hover {
            background-color: #00cc00;
            transform: scale(1.05);
            box-shadow: 0 0 10px #00ff00;
        }

        #username-input {
            margin: 15px 0;
            padding: 10px;
            width: 200px;
            background: black;
            border: 2px solid #00ff00;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            text-align: center;
        }

        #rating-modal {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            z-index: 40;
            text-align: center;
        }

        #rating-modal h2 {
            font-size: 2rem;
            color: #00ff00;
            margin-bottom: 20px;
        }

        .rating-stars {
            display: flex;
            margin: 20px 0;
        }

        .star {
            font-size: 2.5rem;
            color: #555;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0 5px;
        }

        .star:hover, .star.active {
            color: gold;
            transform: scale(1.1);
        }

        #feedback-input {
            width: 80%;
            height: 100px;
            margin: 15px 0;
            padding: 10px;
            background: black;
            border: 2px solid #00ff00;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
        }

        .rating-buttons {
            display: flex;
            gap: 10px;
        }

        .home-button {
            background: none;
            border: none;
            color: #00ff00;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 20px;
        }

        .home-button:hover {
            transform: scale(1.1);
            text-shadow: 0 0 10px #00ff00;
        }

        .controls-info {
            margin-top: 10px;
            font-size: 1rem;
            color: #00ff00;
            width: 600px;
            text-align: center;
        }

        .ground {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 10px;
            background-color: #003300;
            z-index: 1;
            border-top: 2px solid #00ff00;
        }

        #difficulty-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #00ff00;
            font-size: 0.9rem;
            z-index: 20;
        }
    </style>
</head>
<body>
    <div class="game-header">
        <button class="home-button" onclick="window.location.href='home.php'">⌂</button>
        <h1 class="game-title">POU COMILÃO</h1>
    </div>

    <div class="game-stats">
        <div class="stat-box" id="score-display">PONTOS: 0</div>
        <div class="stat-box" id="time-display">TEMPO: 0:00</div>
    </div>

    <div id="game-area">
        <div id="game-container">
            <div class="ground"></div>
            <div id="difficulty-indicator">Dificuldade: 1x</div>
            
            <div id="start-screen">
                <h2>POU COMILÃO</h2>
                <p>Pegue todas as comidas que caem!</p>
                <p>Evite os lixos e não deixe as comidas caírem no chão</p>
                <p>Use as setas ← → para se mover</p>
                <button id="start-button">COMEÇAR JOGO</button>
            </div>
            
            <div id="player"></div>
            
            <div id="game-over">
                <h2 id="game-over-text">GAME OVER!</h2>
                
                <div class="result-stats">
                    <div class="stat-box" id="final-score">PONTOS: 0</div>
                    <div class="stat-box" id="final-time">TEMPO: 0:00</div>
                </div>
                
                <input type="text" id="username-input" placeholder="Digite seu nome" maxlength="15">
                
                <div>
                    <button id="submit-score" class="game-button">ENVIAR PONTUAÇÃO</button>
                    <button id="restart-btn" class="game-button">JOGAR NOVAMENTE</button>
                </div>
            </div>
            
            <div id="rating-modal">
                <h2>Avalie o jogo!</h2>
                <p>Como você avalia sua experiência com o jogo?</p>
                
                <div class="rating-stars">
                    <div class="star" data-rating="1">★</div>
                    <div class="star" data-rating="2">★</div>
                    <div class="star" data-rating="3">★</div>
                    <div class="star" data-rating="4">★</div>
                    <div class="star" data-rating="5">★</div>
                </div>
                
                <textarea id="feedback-input" placeholder="Deixe seu comentário (opcional)"></textarea>
                
                <div class="rating-buttons">
                    <button id="send-rating" class="game-button">ENVIAR</button>
                    <button id="later-rating" class="game-button">MAIS TARDE</button>
                    <button id="no-rating" class="game-button">NÃO</button>
                </div>
            </div>
        </div>

        <div id="ranking-container">
            <h3>🏆 TOP 10 JOGADORES 🏆</h3>
            <ul id="ranking-list">
                <li class="ranking-item">
                    <span>1.</span>
                    <span>---</span>
                    <span>0p</span>
                </li>
            </ul>
        </div>
    </div>

    <div id="controls" class="controls-info">
        CONTROLES: SETAS ESQUERDA e DIREITA | PEGUE AS COMIDAS E EVITE OS LIXOS
    </div>

    <script>
        // =============================================
        // CONFIGURAÇÕES DO JOGO
        // =============================================
        const config = {
            // Configurações de velocidade
            playerSpeed: 8,
            baseFoodSpeed: 3,
            trashSpeed: 4,
            baseSpawnRate: 1000, // ms entre spawns de itens
            
            // Configurações de dificuldade
            difficultyInterval: 25000, // 25 segundos entre aumentos de dificuldade
            speedIncreaseFactor: 1.2,  // Aumento de 20% na velocidade
            spawnRateDecreaseFactor: 0.9, // Redução de 10% no tempo entre spawns
            maxDifficulty: 5,         // Dificuldade máxima (evita ficar impossível)
            
            // Configurações de imagens
            foodImages: [
                'img/pontocook.png',
                'img/pontocook.png',
                'img/pontocook.png'
            ],
            trashImages: [
                'img/pimentinha.png',
                'img/pimentinha.png',
                'img/pimentinha.png'
            ],
            playerImage: 'img/chefprotagonista.png',
            
            // Configurações de avaliação
            showRatingAfterGames: 3,
            retryRatingAfter: {
                later: 20,
                no: 10
            }
        };

        // =============================================
        // ESTADO DO JOGO
        // =============================================
        const gameState = {
            score: 0,
            isRunning: false,
            playerX: 270,
            items: [],
            lastSpawn: 0,
            startTime: null,
            currentTime: 0,
            keys: {
                left: false,
                right: false
            },
            speedMultiplier: 1,
            spawnRate: config.baseSpawnRate,
            lastDifficultyIncrease: 0,
            gamesPlayed: 0,
            ratingShown: false,
            currentDifficulty: 1
        };

        // =============================================
        // ELEMENTOS DOM
        // =============================================
        const elements = {
            container: document.getElementById('game-container'),
            player: document.getElementById('player'),
            scoreDisplay: document.getElementById('score-display'),
            timeDisplay: document.getElementById('time-display'),
            gameOver: document.getElementById('game-over'),
            gameOverText: document.getElementById('game-over-text'),
            finalScore: document.getElementById('final-score'),
            finalTime: document.getElementById('final-time'),
            startScreen: document.getElementById('start-screen'),
            startButton: document.getElementById('start-button'),
            restartButton: document.getElementById('restart-btn'),
            submitScore: document.getElementById('submit-score'),
            usernameInput: document.getElementById('username-input'),
            rankingList: document.getElementById('ranking-list'),
            ratingModal: document.getElementById('rating-modal'),
            stars: document.querySelectorAll('.star'),
            feedbackInput: document.getElementById('feedback-input'),
            sendRating: document.getElementById('send-rating'),
            laterRating: document.getElementById('later-rating'),
            noRating: document.getElementById('no-rating'),
            difficultyIndicator: document.getElementById('difficulty-indicator')
        };

        // =============================================
        // FUNÇÕES DO JOGO
        // =============================================
        function initGame() {
            gameState.score = 0;
            gameState.isRunning = true;
            gameState.playerX = 270;
            gameState.items = [];
            gameState.lastSpawn = 0;
            gameState.startTime = Date.now();
            gameState.currentTime = 0;
            gameState.speedMultiplier = 1;
            gameState.spawnRate = config.baseSpawnRate;
            gameState.lastDifficultyIncrease = Date.now();
            gameState.currentDifficulty = 1;
            
            elements.scoreDisplay.textContent = `PONTOS: ${gameState.score}`;
            elements.timeDisplay.textContent = `TEMPO: 0:00`;
            elements.gameOver.style.display = 'none';
            elements.startScreen.style.display = 'none';
            elements.difficultyIndicator.textContent = `Dificuldade: 1x`;
            
            document.querySelectorAll('.food, .trash').forEach(el => el.remove());
            
            elements.player.style.backgroundImage = `url('${config.playerImage}')`;
            updatePlayerPosition();
            
            requestAnimationFrame(gameLoop);
            updateTimer();
        }

        function updatePlayerPosition() {
            elements.player.style.left = `${gameState.playerX}px`;
        }

        function spawnItem() {
            const now = Date.now();
            if (now - gameState.lastSpawn < gameState.spawnRate) return;
            
            gameState.lastSpawn = now;
            
            const isFood = Math.random() > 0.3;
            const item = document.createElement('div');
            item.className = isFood ? 'food' : 'trash';
            
            const images = isFood ? config.foodImages : config.trashImages;
            const randomImage = images[Math.floor(Math.random() * images.length)];
            item.style.backgroundImage = `url('${randomImage}')`;
            
            const x = Math.random() * (elements.container.offsetWidth - 40);
            item.style.left = `${x}px`;
            item.style.top = '0px';
            
            elements.container.appendChild(item);
            
            gameState.items.push({
                element: item,
                x: x,
                y: 0,
                isFood: isFood,
                speed: (isFood ? config.baseFoodSpeed : config.trashSpeed) * gameState.speedMultiplier
            });
        }

        function updateItems() {
            for (let i = gameState.items.length - 1; i >= 0; i--) {
                const item = gameState.items[i];
                item.y += item.speed;
                item.element.style.top = `${item.y}px`;
                
                // Verifica colisão com o jogador
                if (
                    item.y + 40 >= elements.container.offsetHeight - 70 &&
                    item.x + 40 > gameState.playerX &&
                    item.x < gameState.playerX + 60
                ) {
                    if (item.isFood) {
                        gameState.score++;
                        elements.scoreDisplay.textContent = `PONTOS: ${gameState.score}`;
                    } else {
                        endGame(false);
                    }
                    elements.container.removeChild(item.element);
                    gameState.items.splice(i, 1);
                    continue;
                }
                
                // Verifica se comida caiu no chão
                if (item.y >= elements.container.offsetHeight - 10) {
                    if (item.isFood) {
                        endGame(false);
                    } else {
                        elements.container.removeChild(item.element);
                        gameState.items.splice(i, 1);
                    }
                }
            }
        }

        function increaseDifficulty() {
            const now = Date.now();
            if (now - gameState.lastDifficultyIncrease > config.difficultyInterval && 
                gameState.currentDifficulty < config.maxDifficulty) {
                
                gameState.lastDifficultyIncrease = now;
                gameState.currentDifficulty++;
                
                // Aumenta a velocidade
                gameState.speedMultiplier *= config.speedIncreaseFactor;
                
                // Diminui o tempo entre spawns
                gameState.spawnRate = Math.max(300, gameState.spawnRate * config.spawnRateDecreaseFactor);
                
                // Atualiza indicador de dificuldade
                elements.difficultyIndicator.textContent = `Dificuldade: ${gameState.currentDifficulty}x`;
                
                console.log(`Dificuldade aumentada para ${gameState.currentDifficulty}x`);
            }
        }

        function movePlayer() {
            if (gameState.keys.left && gameState.playerX > 0) {
                gameState.playerX -= config.playerSpeed;
            }
            if (gameState.keys.right && gameState.playerX < elements.container.offsetWidth - 60) {
                gameState.playerX += config.playerSpeed;
            }
            updatePlayerPosition();
        }

        function updateTimer() {
            if (!gameState.isRunning) return;
            
            gameState.currentTime = Math.floor((Date.now() - gameState.startTime) / 1000);
            const minutes = Math.floor(gameState.currentTime / 60);
            const seconds = gameState.currentTime % 60;
            elements.timeDisplay.textContent = `TEMPO: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            setTimeout(updateTimer, 1000);
        }

        function endGame(win) {
            gameState.isRunning = false;
            gameState.gamesPlayed++;
            
            const minutes = Math.floor(gameState.currentTime / 60);
            const seconds = gameState.currentTime % 60;
            const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            elements.gameOverText.textContent = win ? 'VOCÊ VENCEU!' : 'GAME OVER!';
            elements.finalScore.textContent = `PONTOS: ${gameState.score}`;
            elements.finalTime.textContent = `TEMPO: ${timeString}`;
            elements.gameOver.style.display = 'flex';
            
            checkShowRating();
        }

        // =============================================
        // SISTEMA DE RANKING
        // =============================================
        function loadRanking() {
            const savedRanking = localStorage.getItem('pouComilaoRanking');
            gameState.ranking = savedRanking ? JSON.parse(savedRanking) : [];
            updateRanking();
        }

        function updateRanking() {
            elements.rankingList.innerHTML = '';
            
            if (gameState.ranking.length === 0) {
                const li = document.createElement('li');
                li.className = 'ranking-item';
                li.innerHTML = `
                    <span>1.</span>
                    <span>---</span>
                    <span>0p</span>
                `;
                elements.rankingList.appendChild(li);
                return;
            }
            
            gameState.ranking
                .sort((a, b) => b.score - a.score)
                .slice(0, 10)
                .forEach((player, index) => {
                    const li = document.createElement('li');
                    li.className = 'ranking-item';
                    
                    li.innerHTML = `
                        <span>${index + 1}.</span>
                        <span>${player.name}</span>
                        <span>${player.score}p</span>
                    `;
                    
                    elements.rankingList.appendChild(li);
                });
        }

        function addToRanking(name, score) {
            gameState.ranking.push({ name, score });
            
            gameState.ranking.sort((a, b) => b.score - a.score);
            
            if (gameState.ranking.length > 10) {
                gameState.ranking = gameState.ranking.slice(0, 10);
            }
            
            localStorage.setItem('pouComilaoRanking', JSON.stringify(gameState.ranking));
            updateRanking();
        }

        // =============================================
        // SISTEMA DE AVALIAÇÃO
        // =============================================
        function checkShowRating() {
            let ratingSettings;
            try {
                ratingSettings = JSON.parse(localStorage.getItem('pouComilaoRatingSettings'));
                if (!ratingSettings) {
                    ratingSettings = {
                        lastShown: 0,
                        gamesSinceLastShow: 0,
                        shouldShow: true
                    };
                }
            } catch (e) {
                ratingSettings = {
                    lastShown: 0,
                    gamesSinceLastShow: 0,
                    shouldShow: true
                };
            }
            
            ratingSettings.gamesSinceLastShow++;
            
            if (ratingSettings.shouldShow && 
                ratingSettings.gamesSinceLastShow >= config.showRatingAfterGames) {
                showRatingModal();
                ratingSettings.lastShown = Date.now();
                ratingSettings.gamesSinceLastShow = 0;
                localStorage.setItem('pouComilaoRatingSettings', JSON.stringify(ratingSettings));
            } else {
                localStorage.setItem('pouComilaoRatingSettings', JSON.stringify(ratingSettings));
            }
        }

        function showRatingModal() {
            elements.ratingModal.style.display = 'flex';
            gameState.ratingShown = true;
            
            elements.stars.forEach(star => {
                star.classList.remove('active');
                star.dataset.selected = 'false';
            });
            
            elements.feedbackInput.value = '';
        }

        function handleRatingSelection() {
            elements.stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    
                    elements.stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('active');
                            s.dataset.selected = 'true';
                        } else {
                            s.classList.remove('active');
                            s.dataset.selected = 'false';
                        }
                    });
                });
            });
        }

        function submitRating() {
            const selectedStars = document.querySelectorAll('.star[data-selected="true"]');
            const rating = selectedStars.length;
            const feedback = elements.feedbackInput.value.trim();
            
            if (rating > 0) {
                const ratings = JSON.parse(localStorage.getItem('pouComilaoRatings') || '[]');
                ratings.push({
                    rating,
                    feedback,
                    date: new Date().toISOString()
                });
                localStorage.setItem('pouComilaoRatings', JSON.stringify(ratings));
                
                alert('Obrigado por avaliar o jogo!');
                elements.ratingModal.style.display = 'none';
                
                const ratingSettings = JSON.parse(localStorage.getItem('pouComilaoRatingSettings') || '{}');
                ratingSettings.shouldShow = false;
                localStorage.setItem('pouComilaoRatingSettings', JSON.stringify(ratingSettings));
            } else {
                alert('Por favor, selecione uma avaliação com as estrelas.');
            }
        }

        function handleRatingLater() {
            const ratingSettings = JSON.parse(localStorage.getItem('pouComilaoRatingSettings') || '{}');
            ratingSettings.gamesSinceLastShow = -config.retryRatingAfter.later;
            localStorage.setItem('pouComilaoRatingSettings', JSON.stringify(ratingSettings));
            
            elements.ratingModal.style.display = 'none';
        }

        function handleRatingNo() {
            const ratingSettings = JSON.parse(localStorage.getItem('pouComilaoRatingSettings') || '{}');
            ratingSettings.gamesSinceLastShow = -config.retryRatingAfter.no;
            localStorage.setItem('pouComilaoRatingSettings', JSON.stringify(ratingSettings));
            
            elements.ratingModal.style.display = 'none';
        }

        // =============================================
        // LOOP PRINCIPAL DO JOGO
        // =============================================
        function gameLoop() {
            if (!gameState.isRunning) return;
            
            increaseDifficulty();
            movePlayer();
            spawnItem();
            updateItems();
            
            requestAnimationFrame(gameLoop);
        }

        // =============================================
        // EVENT LISTENERS
        // =============================================
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') gameState.keys.left = true;
            if (e.key === 'ArrowRight') gameState.keys.right = true;
        });

        document.addEventListener('keyup', (e) => {
            if (e.key === 'ArrowLeft') gameState.keys.left = false;
            if (e.key === 'ArrowRight') gameState.keys.right = false;
        });

        elements.startButton.addEventListener('click', initGame);
        elements.restartButton.addEventListener('click', () => {
            elements.gameOver.style.display = 'none';
            initGame();
        });

        elements.submitScore.addEventListener('click', () => {
            const username = elements.usernameInput.value.trim();
            if (username) {
                addToRanking(username, gameState.score);
                elements.usernameInput.value = '';
                elements.gameOver.style.display = 'none';
                initGame();
            } else {
                alert('Por favor, digite seu nome!');
            }
        });

        handleRatingSelection();
        elements.sendRating.addEventListener('click', submitRating);
        elements.laterRating.addEventListener('click', handleRatingLater);
        elements.noRating.addEventListener('click', handleRatingNo);

        loadRanking();
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pac-Man Culinário</title>
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
            width: 30px;
            height: 30px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/599/599516.png');
            background-size: cover;
            z-index: 10;
            transition: all 0.2s ease;
        }

        .dot {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #FFD700;
            border-radius: 50%;
            z-index: 5;
        }

        .power-pellet {
            position: absolute;
            width: 20px;
            height: 20px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/3170/3170733.png');
            background-size: cover;
            z-index: 5;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .ghost {
            position: absolute;
            width: 30px;
            height: 30px;
            background-size: cover;
            z-index: 8;
            transition: all 0.3s ease;
        }

        .wall {
            position: absolute;
            background-color: #003300;
            border: 2px solid #00ff00;
            z-index: 1;
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
            padding: 10px;
            border-radius: 50%;
            background-color: rgba(0, 255, 0, 0.2);
            box-shadow: 0 0 10px #00ff00;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
        }

        .home-button:hover {
            transform: scale(1.1);
            background-color: rgba(0, 255, 0, 0.3);
            box-shadow: 0 0 15px #00ff00;
        }

        .back-button {
            background: none;
            border: none;
            color: #00ff00;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 10px;
            padding: 10px;
            border-radius: 50%;
            background-color: rgba(0, 255, 0, 0.2);
            box-shadow: 0 0 10px #00ff00;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
        }

        .back-button:hover {
            transform: scale(1.1);
            background-color: rgba(0, 255, 0, 0.3);
            box-shadow: 0 0 15px #00ff00;
        }

        .controls-info {
            margin-top: 10px;
            font-size: 1rem;
            color: #00ff00;
            width: 600px;
            text-align: center;
        }

        #maze {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="game-header">
        <button class="back-button" onclick="window.location.href='jogos.php'">↩</button>
        <button class="home-button" onclick="window.location.href='home.php'">⌂</button>
        <h1 class="game-title">PAC-MAN CULINÁRIO</h1>
    </div>

    <div class="game-stats">
        <div class="stat-box" id="score-display">PONTOS: 0</div>
        <div class="stat-box" id="time-display">TEMPO: 0:00</div>
    </div>

    <div id="game-area">
        <div id="game-container">
            <div id="maze"></div>
            
            <div id="start-screen">
                <h2>PAC-MAN CULINÁRIO</h2>
                <p>Colete todos os ingredientes antes que os chefs fantasmas te peguem!</p>
                <p>Use WASD ou as setas do teclado para se mover</p>
                <button id="start-button">COMEÇAR JOGO</button>
            </div>
            
            <div id="player"></div>
            
            <div id="game-over">
                <h2 id="game-over-text">VOCÊ VENCEU!</h2>
                
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
                    <span>0:00</span>
                </li>
            </ul>
        </div>
    </div>

    <div id="controls" class="controls-info">
        CONTROLES: WASD ou SETAS DO TECLADO | COLETE TODOS OS INGREDIENTES
        <div class="controls-info">Pressione R para reiniciar a qualquer momento</div>
    </div>

    <script>
        // Configurações do jogo
        const config = {
            gridSize: 30,
            gridWidth: 20,
            gridHeight: 15,
            playerSpeed: 150,
            ghostSpeed: 120,
            scaredGhostSpeed: 80,
            scaredTime: 10000, // 10 segundos
            dotCount: 100,
            powerPelletCount: 4,
            ghostImages: [
                'https://cdn-icons-png.flaticon.com/512/3082/3082383.png', // Chef vermelho
                'https://cdn-icons-png.flaticon.com/512/3170/3170733.png', // Chef rosa
                'https://cdn-icons-png.flaticon.com/512/2927/2927347.png', // Chef azul
                'https://cdn-icons-png.flaticon.com/512/2518/2518029.png'  // Chef laranja
            ],
            scaredGhostImage: 'https://cdn-icons-png.flaticon.com/512/484/484611.png', // Chef assustado
            playerImage: 'https://cdn-icons-png.flaticon.com/512/599/599516.png', // Chef principal
            powerPelletImage: 'https://cdn-icons-png.flaticon.com/512/2617/2617039.png', // Pimenta
            showRatingAfterGames: 3,
            retryRatingAfter: {
                later: 20,
                no: 10
            }
        };

        // Estado do jogo
        const gameState = {
            score: 0,
            isRunning: false,
            startTime: null,
            currentTime: 0,
            player: { x: 0, y: 0, dx: 0, dy: 0, nextDir: null },
            ghosts: [],
            dots: [],
            powerPellets: [],
            walls: [],
            ranking: [],
            lastUpdate: 0,
            keys: {},
            scaredTimer: 0,
            gamesPlayed: 0,
            ratingShown: false
        };

        // Elementos DOM
        const elements = {
            container: document.getElementById('game-container'),
            maze: document.getElementById('maze'),
            player: document.getElementById('player'),
            scoreDisplay: document.getElementById('score-display'),
            timeDisplay: document.getElementById('time-display'),
            gameOver: document.getElementById('game-over'),
            gameOverText: document.getElementById('game-over-text'),
            finalScore: document.getElementById('final-score'),
            finalTime: document.getElementById('final-time'),
            restartBtn: document.getElementById('restart-btn'),
            submitScore: document.getElementById('submit-score'),
            usernameInput: document.getElementById('username-input'),
            rankingList: document.getElementById('ranking-list'),
            startScreen: document.getElementById('start-screen'),
            startButton: document.getElementById('start-button'),
            ratingModal: document.getElementById('rating-modal'),
            stars: document.querySelectorAll('.star'),
            feedbackInput: document.getElementById('feedback-input'),
            sendRating: document.getElementById('send-rating'),
            laterRating: document.getElementById('later-rating'),
            noRating: document.getElementById('no-rating')
        };

        // Layout do labirinto (0 = caminho, 1 = parede)
        const mazeLayout = [
            [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
            [1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1],
            [1,0,1,1,0,1,1,1,0,1,0,1,1,1,0,1,1,1,0,1],
            [1,0,1,1,0,1,1,1,0,1,0,1,1,1,0,1,1,1,0,1],
            [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1],
            [1,0,1,1,0,1,0,1,1,1,1,1,0,1,0,1,1,1,0,1],
            [1,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,0,1],
            [1,1,1,1,0,1,1,1,0,1,0,1,1,1,0,1,1,1,1,1],
            [0,0,0,1,0,1,0,0,0,0,0,0,0,1,0,1,0,0,0,0],
            [1,1,1,1,0,1,0,1,1,0,1,1,0,1,0,1,1,1,1,1],
            [1,0,0,0,0,0,0,1,0,0,0,1,0,0,0,0,0,0,0,1],
            [1,0,1,1,0,1,0,1,1,1,1,1,0,1,0,1,1,1,0,1],
            [1,0,0,1,0,1,0,0,0,0,0,0,0,1,0,1,0,0,0,1],
            [1,1,0,1,0,1,0,1,1,1,1,1,0,1,0,1,0,1,1,1],
            [1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1],
            [1,0,1,1,0,1,1,1,0,1,0,1,1,1,0,1,1,1,0,1],
            [1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1],
            [1,1,0,1,0,1,0,1,1,1,1,1,0,1,0,1,0,1,1,1],
            [1,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,0,1],
            [1,0,1,1,1,1,1,1,0,1,0,1,1,1,1,1,1,1,0,1],
            [1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1],
            [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
        ];

        // Inicialização do jogo
        function initGame() {
            gameState.score = 0;
            gameState.isRunning = true;
            gameState.startTime = Date.now();
            gameState.currentTime = 0;
            gameState.player = { 
                x: 1,
                y: 1,
                dx: 0,
                dy: 0,
                nextDir: null
            };
            gameState.ghosts = [];
            gameState.dots = [];
            gameState.powerPellets = [];
            gameState.walls = [];
            gameState.lastUpdate = Date.now();
            gameState.keys = {};
            gameState.scaredTimer = 0;
            
            elements.scoreDisplay.textContent = `PONTOS: ${gameState.score}`;
            elements.timeDisplay.textContent = `TEMPO: 0:00`;
            elements.gameOver.style.display = 'none';
            document.querySelectorAll('.dot, .power-pellet, .ghost, .wall').forEach(el => el.remove());
            
            createMaze();
            createDots();
            createPowerPellets();
            createGhosts();
            updatePlayerPosition();
            
            loadRanking();
            requestAnimationFrame(gameLoop);
            updateTimer();
        }

        // Cria o labirinto
        function createMaze() {
            for (let y = 0; y < mazeLayout.length; y++) {
                for (let x = 0; x < mazeLayout[y].length; x++) {
                    if (mazeLayout[y][x] === 1) {
                        createWall(x, y);
                    }
                }
            }
        }

        function createWall(x, y) {
            const wall = document.createElement('div');
            wall.className = 'wall';
            wall.style.width = config.gridSize + 'px';
            wall.style.height = config.gridSize + 'px';
            wall.style.left = (x * config.gridSize) + 'px';
            wall.style.top = (y * config.gridSize) + 'px';
            elements.maze.appendChild(wall);
            
            gameState.walls.push({ x, y });
        }

        // Cria os pontos
        function createDots() {
            for (let y = 0; y < mazeLayout.length; y++) {
                for (let x = 0; x < mazeLayout[y].length; x++) {
                    if (mazeLayout[y][x] === 0 && 
                        !(x === 1 && y === 1) && // Não coloca ponto na posição inicial do jogador
                        !isPowerPelletPosition(x, y)) {
                        
                        const dot = document.createElement('div');
                        dot.className = 'dot';
                        dot.style.left = (x * config.gridSize + config.gridSize/2 - 5) + 'px';
                        dot.style.top = (y * config.gridSize + config.gridSize/2 - 5) + 'px';
                        elements.container.appendChild(dot);
                        
                        gameState.dots.push({ 
                            x, 
                            y, 
                            element: dot 
                        });
                    }
                }
            }
        }

        // Verifica se é uma posição de power pellet
        function isPowerPelletPosition(x, y) {
            const pelletPositions = [
                {x: 1, y: 1},
                {x: 18, y: 1},
                {x: 1, y: 19},
                {x: 18, y: 19}
            ];
            
            return pelletPositions.some(pos => pos.x === x && pos.y === y);
        }

        // Cria os power pellets
        function createPowerPellets() {
            const positions = [
                {x: 1, y: 1},
                {x: 18, y: 1},
                {x: 1, y: 19},
                {x: 18, y: 19}
            ];
            
            positions.forEach(pos => {
                const pellet = document.createElement('div');
                pellet.className = 'power-pellet';
                pellet.style.left = (pos.x * config.gridSize + config.gridSize/2 - 10) + 'px';
                pellet.style.top = (pos.y * config.gridSize + config.gridSize/2 - 10) + 'px';
                elements.container.appendChild(pellet);
                
                gameState.powerPellets.push({ 
                    x: pos.x, 
                    y: pos.y, 
                    element: pellet 
                });
            });
        }

        // Cria os fantasmas
        function createGhosts() {
            const positions = [
                {x: 9, y: 9, color: 0}, // Vermelho
                {x: 9, y: 8, color: 1}, // Rosa
                {x: 8, y: 9, color: 2}, // Azul
                {x: 10, y: 9, color: 3} // Laranja
            ];
            
            positions.forEach((pos, index) => {
                const ghost = document.createElement('div');
                ghost.className = 'ghost';
                ghost.style.backgroundImage = `url('${config.ghostImages[pos.color]}')`;
                ghost.style.left = (pos.x * config.gridSize) + 'px';
                ghost.style.top = (pos.y * config.gridSize) + 'px';
                elements.container.appendChild(ghost);
                
                gameState.ghosts.push({
                    x: pos.x,
                    y: pos.y,
                    dx: index % 2 === 0 ? 1 : -1,
                    dy: 0,
                    element: ghost,
                    color: pos.color,
                    isScared: false
                });
            });
        }

        // Atualiza posições
        function updatePlayerPosition() {
            elements.player.style.left = (gameState.player.x * config.gridSize) + 'px';
            elements.player.style.top = (gameState.player.y * config.gridSize) + 'px';
        }

        function updateGhostPosition(ghost) {
            ghost.element.style.left = (ghost.x * config.gridSize) + 'px';
            ghost.element.style.top = (ghost.y * config.gridSize) + 'px';
            
            if (ghost.isScared) {
                ghost.element.style.backgroundImage = `url('${config.scaredGhostImage}')`;
            } else {
                ghost.element.style.backgroundImage = `url('${config.ghostImages[ghost.color]}')`;
            }
        }

        // Verifica colisão com paredes
        function isWallCollision(x, y) {
            return gameState.walls.some(wall => wall.x === x && wall.y === y);
        }

        // Verifica se a posição está dentro dos limites do labirinto
        function isValidPosition(x, y) {
            return x >= 0 && x < config.gridWidth && y >= 0 && y < config.gridHeight;
        }

        // Movimenta o jogador
        function movePlayer() {
            // Verifica se há uma próxima direção e se é possível mover nela
            if (gameState.player.nextDir) {
                const nextX = gameState.player.x + gameState.player.nextDir.dx;
                const nextY = gameState.player.y + gameState.player.nextDir.dy;
                
                if (isValidPosition(nextX, nextY) && !isWallCollision(nextX, nextY)) {
                    gameState.player.dx = gameState.player.nextDir.dx;
                    gameState.player.dy = gameState.player.nextDir.dy;
                    gameState.player.nextDir = null;
                }
            }
            
            const newX = gameState.player.x + gameState.player.dx;
            const newY = gameState.player.y + gameState.player.dy;
            
            if (isValidPosition(newX, newY) && !isWallCollision(newX, newY)) {
                gameState.player.x = newX;
                gameState.player.y = newY;
                
                // Verifica se saiu por um túnel
                if (gameState.player.x < 0) {
                    gameState.player.x = config.gridWidth - 1;
                } else if (gameState.player.x >= config.gridWidth) {
                    gameState.player.x = 0;
                }
                
                checkDotCollision();
                checkPowerPelletCollision();
                checkGhostCollision();
            }
            
            updatePlayerPosition();
        }

        // Verifica colisão com pontos
        function checkDotCollision() {
            for (let i = gameState.dots.length - 1; i >= 0; i--) {
                const dot = gameState.dots[i];
                if (gameState.player.x === dot.x && gameState.player.y === dot.y) {
                    elements.container.removeChild(dot.element);
                    gameState.dots.splice(i, 1);
                    gameState.score += 10;
                    elements.scoreDisplay.textContent = `PONTOS: ${gameState.score}`;
                    
                    // Verifica vitória
                    if (gameState.dots.length === 0 && gameState.powerPellets.length === 0) {
                        endGame(true);
                    }
                }
            }
        }

        // Verifica colisão com power pellets
        function checkPowerPelletCollision() {
            for (let i = gameState.powerPellets.length - 1; i >= 0; i--) {
                const pellet = gameState.powerPellets[i];
                if (gameState.player.x === pellet.x && gameState.player.y === pellet.y) {
                    elements.container.removeChild(pellet.element);
                    gameState.powerPellets.splice(i, 1);
                    gameState.score += 50;
                    elements.scoreDisplay.textContent = `PONTOS: ${gameState.score}`;
                    
                    // Ativa modo assustado nos fantasmas
                    gameState.ghosts.forEach(ghost => {
                        ghost.isScared = true;
                        updateGhostPosition(ghost);
                    });
                    
                    gameState.scaredTimer = Date.now() + config.scaredTime;
                    
                    // Verifica vitória
                    if (gameState.dots.length === 0 && gameState.powerPellets.length === 0) {
                        endGame(true);
                    }
                }
            }
        }

        // Verifica colisão com fantasmas
        function checkGhostCollision() {
            gameState.ghosts.forEach(ghost => {
                if (gameState.player.x === ghost.x && gameState.player.y === ghost.y) {
                    if (ghost.isScared) {
                        // Come o fantasma
                        ghost.x = 9;
                        ghost.y = 9;
                        ghost.isScared = false;
                        updateGhostPosition(ghost);
                        gameState.score += 200;
                        elements.scoreDisplay.textContent = `PONTOS: ${gameState.score}`;
                    } else {
                        // Game over
                        endGame(false);
                    }
                }
            });
        }

        // Movimenta os fantasmas
        function moveGhosts() {
            gameState.ghosts.forEach(ghost => {
                // Verifica se o modo assustado acabou
                if (ghost.isScared && Date.now() > gameState.scaredTimer) {
                    ghost.isScared = false;
                    updateGhostPosition(ghost);
                }
                
                // Tenta continuar na mesma direção
                const newX = ghost.x + ghost.dx;
                const newY = ghost.y + ghost.dy;
                
                if (isValidPosition(newX, newY) && !isWallCollision(newX, newY)) {
                    ghost.x = newX;
                    ghost.y = newY;
                } else {
                    // Muda de direção aleatoriamente
                    const directions = [];
                    
                    if (!isWallCollision(ghost.x, ghost.y - 1)) directions.push({dx: 0, dy: -1});
                    if (!isWallCollision(ghost.x, ghost.y + 1)) directions.push({dx: 0, dy: 1});
                    if (!isWallCollision(ghost.x - 1, ghost.y)) directions.push({dx: -1, dy: 0});
                    if (!isWallCollision(ghost.x + 1, ghost.y)) directions.push({dx: 1, dy: 0});
                    
                    // Remove a direção oposta à atual para não ficar oscilando
                    const oppositeDir = {dx: -ghost.dx, dy: -ghost.dy};
                    const validDirections = directions.filter(dir => 
                        !(dir.dx === oppositeDir.dx && dir.dy === oppositeDir.dy));
                    
                    if (validDirections.length > 0) {
                        const dir = validDirections[Math.floor(Math.random() * validDirections.length)];
                        ghost.dx = dir.dx;
                        ghost.dy = dir.dy;
                        ghost.x += ghost.dx;
                        ghost.y += ghost.dy;
                    }
                }
                
                // Verifica se saiu por um túnel
                if (ghost.x < 0) {
                    ghost.x = config.gridWidth - 1;
                } else if (ghost.x >= config.gridWidth) {
                    ghost.x = 0;
                }
                
                updateGhostPosition(ghost);
            });
        }

        // Atualiza o timer
        function updateTimer() {
            if (!gameState.isRunning) return;
            
            gameState.currentTime = Math.floor((Date.now() - gameState.startTime) / 1000);
            const minutes = Math.floor(gameState.currentTime / 60);
            const seconds = gameState.currentTime % 60;
            elements.timeDisplay.textContent = `TEMPO: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            setTimeout(updateTimer, 1000);
        }

        // Finaliza o jogo
        function endGame(win) {
            gameState.isRunning = false;
            gameState.gamesPlayed++;
            
            const minutes = Math.floor(gameState.currentTime / 60);
            const seconds = gameState.currentTime % 60;
            const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            if (win) {
                elements.gameOverText.textContent = 'VOCÊ VENCEU!';
            } else {
                elements.gameOverText.textContent = 'GAME OVER!';
            }
            
            elements.finalScore.textContent = `PONTOS: ${gameState.score}`;
            elements.finalTime.textContent = `TEMPO: ${timeString}`;
            elements.gameOver.style.display = 'flex';
            
            checkShowRating();
        }

        // Carrega ranking
        function loadRanking() {
            const savedRanking = localStorage.getItem('pacmanRanking');
            gameState.ranking = savedRanking ? JSON.parse(savedRanking) : [];
            updateRanking();
        }

        // Atualiza ranking na tela
        function updateRanking() {
            elements.rankingList.innerHTML = '';
            
            if (gameState.ranking.length === 0) {
                const li = document.createElement('li');
                li.className = 'ranking-item';
                li.innerHTML = `
                    <span>1.</span>
                    <span>---</span>
                    <span>0p</span>
                    <span>0:00</span>
                `;
                elements.rankingList.appendChild(li);
                return;
            }
            
            gameState.ranking
                .sort((a, b) => b.score - a.score || a.time - b.time)
                .slice(0, 10)
                .forEach((player, index) => {
                    const li = document.createElement('li');
                    li.className = 'ranking-item';
                    
                    const minutes = Math.floor(player.time / 60);
                    const seconds = player.time % 60;
                    const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    
                    li.innerHTML = `
                        <span>${index + 1}.</span>
                        <span>${player.name}</span>
                        <span>${player.score}p</span>
                        <span>${timeString}</span>
                    `;
                    
                    elements.rankingList.appendChild(li);
                });
        }

        // Adiciona ao ranking
        function addToRanking(name, time, score) {
            gameState.ranking.push({ name, time, score });
            
            // Ordena por pontuação (maior primeiro) e depois por tempo (menor primeiro)
            gameState.ranking.sort((a, b) => b.score - a.score || a.time - b.time);
            
            // Mantém apenas os 10 melhores
            if (gameState.ranking.length > 10) {
                gameState.ranking = gameState.ranking.slice(0, 10);
            }
            
            localStorage.setItem('pacmanRanking', JSON.stringify(gameState.ranking));
            updateRanking();
        }

        // Sistema de avaliação
        function checkShowRating() {
            let ratingSettings;
            try {
                ratingSettings = JSON.parse(localStorage.getItem('pacmanRatingSettings'));
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
                localStorage.setItem('pacmanRatingSettings', JSON.stringify(ratingSettings));
            } else {
                localStorage.setItem('pacmanRatingSettings', JSON.stringify(ratingSettings));
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
                const ratings = JSON.parse(localStorage.getItem('pacmanRatings') || '[]');
                ratings.push({
                    rating,
                    feedback,
                    date: new Date().toISOString()
                });
                localStorage.setItem('pacmanRatings', JSON.stringify(ratings));
                
                alert('Obrigado por avaliar o jogo!');
                elements.ratingModal.style.display = 'none';
                
                const ratingSettings = JSON.parse(localStorage.getItem('pacmanRatingSettings') || '{}');
                ratingSettings.shouldShow = false;
                localStorage.setItem('pacmanRatingSettings', JSON.stringify(ratingSettings));
            } else {
                alert('Por favor, selecione uma avaliação com as estrelas.');
            }
        }

        function handleRatingLater() {
            const ratingSettings = JSON.parse(localStorage.getItem('pacmanRatingSettings') || '{}');
            ratingSettings.gamesSinceLastShow = -config.retryRatingAfter.later;
            localStorage.setItem('pacmanRatingSettings', JSON.stringify(ratingSettings));
            
            elements.ratingModal.style.display = 'none';
        }

        function handleRatingNo() {
            const ratingSettings = JSON.parse(localStorage.getItem('pacmanRatingSettings') || '{}');
            ratingSettings.gamesSinceLastShow = -config.retryRatingAfter.no;
            localStorage.setItem('pacmanRatingSettings', JSON.stringify(ratingSettings));
            
            elements.ratingModal.style.display = 'none';
        }

        // Loop principal do jogo
        function gameLoop() {
            if (!gameState.isRunning) return;
            
            movePlayer();
            moveGhosts();
            
            requestAnimationFrame(gameLoop);
        }

        // Controles
        document.addEventListener('keydown', (e) => {
            const key = e.key.toLowerCase();
            
            // Tecla R reinicia o jogo
            if (key === 'r') {
                initGame();
                return;
            }
            
            if (!gameState.isRunning) return;
            
            // Mapeia teclas para direções
            const keyMap = {
                'arrowup': {dx: 0, dy: -1}, 'w': {dx: 0, dy: -1},
                'arrowdown': {dx: 0, dy: 1}, 's': {dx: 0, dy: 1},
                'arrowleft': {dx: -1, dy: 0}, 'a': {dx: -1, dy: 0},
                'arrowright': {dx: 1, dy: 0}, 'd': {dx: 1, dy: 0}
            };
            
            if (keyMap[key]) {
                gameState.player.nextDir = keyMap[key];
            }
        });

        // Event listeners
        elements.restartBtn.addEventListener('click', () => {
            elements.gameOver.style.display = 'none';
            initGame();
        });

        elements.submitScore.addEventListener('click', () => {
            const username = elements.usernameInput.value.trim();
            if (username) {
                addToRanking(username, gameState.currentTime, gameState.score);
                elements.usernameInput.value = '';
                elements.gameOver.style.display = 'none';
                initGame();
            } else {
                alert('Por favor, digite seu nome!');
            }
        });

        elements.startButton.addEventListener('click', () => {
            elements.startScreen.style.display = 'none';
            initGame();
        });

        // Sistema de avaliação
        handleRatingSelection();
        elements.sendRating.addEventListener('click', submitRating);
        elements.laterRating.addEventListener('click', handleRatingLater);
        elements.noRating.addEventListener('click', handleRatingNo);

        // Carrega ranking inicial
        loadRanking();
    </script>
</body>
</html>
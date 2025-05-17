<?php
session_start();

// Processamento do login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "../db_conn.php";
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $is_staff_login = isset($_POST['staff_login']);

    // Verificação básica de campos
    if (empty($username) || empty($password)) {
        $error = 'Por favor, preencha todos os campos';
    } else {
        try {
            if ($is_staff_login) {
                // Login para funcionários (admin)
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND is_admin = 1");
            } else {
                // Login normal
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            }
            
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                header('Location: home.php');
                exit();
            } else {
                $error = 'Usuário ou senha incorretos';
                if ($is_staff_login) {
                    $error .= ' (ou você não tem acesso de funcionário)';
                }
            }
        } catch (PDOException $e) {
            $error = 'Erro ao conectar com o banco de dados';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Feed - Login</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="feed-preview">
                <div class="feed-preview-content">
                    <div class="logo-container">
                        <div class="logo">
                            <img src="imgf/chapeu-de-chef.png" alt="" width="80px" height="80px">
                        </div>
                        <h2 class="logo-text">Chef Feed</h2>
                        <p class="logo-tagline">Descubra receitas incríveis</p>
                    </div>
                    <div class="feed-post">
                        <div class="post-header">
                            <div class="profile-pic">
                                <img id="profile-pic" src="imgf/Julia.png" alt="Profile" />
                            </div>
                            <span id="username" class="username">chef_julia08</span>
                        </div>
                        <div class="post-image">
                            <img id="post-image" src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1480" alt="Delicious Food" />
                        </div>
                        <div class="post-actions">
                            <div class="action-buttons">
                                <i data-lucide="heart" class="action-icon"></i>
                                <i data-lucide="message-circle" class="action-icon"></i>
                                <div class="bookmark-container">
                                    <i data-lucide="bookmark" class="action-icon"></i>
                                </div>
                            </div>
                            <div class="likes">646 curtidas</div>
                            <div class="caption">
                                <span id="caption-username" class="username">chef_julia:</span>
                                <span class="caption-text">Salada de Legumes🥗✨</span>
                            </div>
                            <div class="comments-link">Ver todos os 87 comentários</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="login-section">
                <div class="mobile-logo">
                    <h2 class="logo-text">Chef Feed</h2>
                    <p class="logo-tagline">Descubra receitas incríveis</p>
                </div>
                <h1 class="login-title">Faça login na sua conta</h1>
                <?php if (!empty($error)): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form id="login-form" class="login-form" action="login.php" method="POST">
                    <div class="input-group">
                        <div class="input-icon">
                            <i data-lucide="user" class="input-icon-svg"></i>
                        </div>
                        <input type="text" name="username" class="form-input" placeholder="Nome de usuário" required/>
                    </div>
                    <div class="input-group">
                        <div class="input-icon">
                            <i data-lucide="lock" class="input-icon-svg"></i>
                        </div>
                        <input type="password" name="password" class="form-input" placeholder="Senha" required />
                    </div>
                    <button type="submit" class="login-button">Entrar</button>
                </form>
                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">OU</span>
                    <div class="divider-line"></div>
                </div>
                <div class="staff-login">
                    <button id="show-staff-login" class="staff-login-btn">
                        <i data-lucide="shield"></i> Acesso para funcionários
                    </button>
                    <form id="staff-login-form" class="login-form" action="login.php" method="POST" style="display: none;">
                        <input type="hidden" name="staff_login" value="1">
                        <div class="input-group">
                            <div class="input-icon">
                                <i data-lucide="user" class="input-icon-svg"></i>
                            </div>
                            <input type="text" name="username" class="form-input" placeholder="Nome de usuário" required/>
                        </div>
                        <div class="input-group">
                            <div class="input-icon">
                                <i data-lucide="lock" class="input-icon-svg"></i>
                            </div>
                            <input type="password" name="password" class="form-input" placeholder="Senha" required />
                        </div>
                        <button type="submit" class="login-button staff">
                            <i data-lucide="shield"></i> Entrar como funcionário
                        </button>
                    </form>
                </div>
                <div class="forgot-password">
                    <a href="recuperar.php" class="forgot-link">Esqueceu a Senha?</a>
                </div>
                <div class="signup-link">
                    <p class="signup-text">Não tem uma conta? <a href="cadastro.php" class="signup-anchor">Criar</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/login.js"></script>
    <script>
        // Mostrar/ocultar formulário de login para funcionários
        document.getElementById('show-staff-login').addEventListener('click', function() {
            const staffForm = document.getElementById('staff-login-form');
            const normalForm = document.getElementById('login-form');
            
            if (staffForm.style.display === 'none') {
                staffForm.style.display = 'block';
                normalForm.style.display = 'none';
                this.textContent = 'Acesso normal';
            } else {
                staffForm.style.display = 'none';
                normalForm.style.display = 'block';
                this.textContent = 'Acesso para funcionários';
            }
        });
    </script>
</body>
</html>
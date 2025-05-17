<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['fname'])) {
    include "../db_conn.php";
    include 'php/User.php';
    $user = getUserById($_SESSION['id'], $conn);

    // Verificar se o usuário é admin
    $is_admin = isset($user['is_admin']) && $user['is_admin'] == 1;

    // Buscar posts
    try {
        $stmt = $conn->prepare("
            SELECT p.*, u.username, u.pp, 
                   (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS likes_count,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count,
                   EXISTS(SELECT 1 FROM likes WHERE post_id = p.id AND user_id = :user_id) AS liked,
                   EXISTS(SELECT 1 FROM saved_posts WHERE post_id = p.id AND user_id = :user_id) AS saved
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([':user_id' => $_SESSION['id']]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $posts = [];
        error_log("Erro ao buscar posts: " . $e->getMessage());
    }

    // Buscar sugestões
    try {
        $suggestions_stmt = $conn->prepare("
            SELECT id, username, pp FROM users 
            WHERE id != :current_user_id 
            ORDER BY RAND() LIMIT 5
        ");
        $suggestions_stmt->execute([':current_user_id' => $_SESSION['id']]);
        $suggestions = $suggestions_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $suggestions = [];
        error_log("Erro ao buscar sugestões: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Feed</title>
    <link rel="stylesheet" href="css/home.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <div class="emoji-bg"></div>

    <!-- Barra Lateral -->
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <div class="logo-icon">
                    <img src="imgf/chapeu-de-chef.png" alt="" class="logo">
                </div>
                <div class="logo_name">Chef Feed</div>
            </div>
            <i class='bx bx-menu' id="btn"></i>
        </div>

        <ul class="nav_list">
            <li>
                <a href="home.php">
                    <i class='bx bx-home'></i>
                    <span class="links_name">Página Principal</span>
                    <span class="tooltip">Página Principal</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="document.getElementById('create-recipe-modal').style.display='block'">
                    <i class='bx bx-plus-circle'></i>
                    <span class="links_name">Criar</span>
                    <span class="tooltip">Criar</span>
                </a>
            </li>
            <li>
                <a href="culinaria.php">
                    <i class='bx bx-compass'></i>
                    <span class="links_name">Explorar</span>
                    <span class="tooltip">Explorar</span>
                </a>
            </li>
            <li>
                <a href="perfil.php?id=<?= $_SESSION['id'] ?>">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Perfil</span>
                    <span class="tooltip">Perfil</span>
                </a>
            </li>
            <li>
                <a href="jogos.php">
                    <i class='bx bx-game'></i>
                    <span class="links_name">Jogos</span>
                    <span class="tooltip">Jogos</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="document.getElementById('settings-modal').style.display='flex'">
                    <i class='bx bx-cog'></i>
                    <span class="links_name">Configurações</span>
                    <span class="tooltip">Configurações</span>
                </a>
            </li>
            <?php if($is_admin): ?>
            <li>
                <a href="admin.php">
                    <i class='bx bx-shield'></i>
                    <span class="links_name">Admin</span>
                    <span class="tooltip">Painel Admin</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <?php
                    $profilePic = !empty($user['pp']) && file_exists("../upload/" . $user['pp'])
                        ? "../upload/" . htmlspecialchars($user['pp'])
                        : "imgf/default-profile.png";
                    ?>
                    <img src="<?= $profilePic ?>" alt="Foto de perfil">
                    <div class="name_job">
                        <div class="name"><?= htmlspecialchars($user['username']) ?></div>
                        <div class="job">@<?= htmlspecialchars($user['username']) ?></div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class='bx bx-log-out' id="log_out"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="home_content">
        <!-- Barra de Pesquisa -->
        <div class="barra-de-pesquisa">
            <div class="busca-container">
                <i class='bx bx-search'></i>
                <input type="text" class="busca-topo" placeholder="Pesquisar perfis..." id="search-bar">
                <div id="search-results" class="search-results"></div>
            </div>
        </div>

        <div class="main-container">
            <div class="main-content">
                <!-- Feed de Posts -->
                <div class="posts-feed">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post):
                            $postPic = !empty($post['photo_url']) ? htmlspecialchars($post['photo_url']) : '';
                            $userPic = !empty($post['pp']) ? "../upload/" . htmlspecialchars($post['pp']) : "imgf/default-profile.png";
                        ?>
                            <div class="post" data-post-id="<?= $post['id'] ?>">
                                <div class="post-header">
                                    <a href="perfil.php?id=<?= $post['user_id'] ?>">
                                        <img src="<?= $userPic ?>" alt="Perfil" class="post-profile-pic">
                                    </a>
                                    <a href="perfil.php?id=<?= $post['user_id'] ?>" class="post-username"><?= htmlspecialchars($post['username']) ?></a>
                                    <div class="post-options">
                                        <button class="follow-btn" data-user-id="<?= $post['user_id'] ?>">Seguir</button>
                                        <button class="options-btn"><i data-lucide="more-vertical"></i></button>
                                        <div class="options-menu">
                                            <div class="option-item save-post">Salvar</div>
                                            <div class="option-item report" onclick="reportPost(<?= $post['id'] ?>)">Denunciar</div>
                                            <?php if($is_admin || $_SESSION['id'] == $post['user_id']): ?>
                                            <div class="option-item delete-post" onclick="deletePost(<?= $post['id'] ?>)">Excluir</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($postPic): ?>
                                    <div class="post-image-container">
                                        <img src="<?= $postPic ?>" alt="Receita" class="post-image">
                                    </div>
                                <?php endif; ?>

                                <div class="post-content">
                                    <div class="post-actions">
                                        <button class="action-btn like-btn <?= $post['liked'] ? 'liked' : '' ?>">
                                            <i class='bx <?= $post['liked'] ? 'bxs-heart' : 'bx-heart' ?>'></i>
                                        </button>
                                        <button class="action-btn comment-btn">
                                            <i class='bx bx-comment'></i>
                                        </button>
                                        <button class="action-btn save-btn <?= $post['saved'] ? 'saved' : '' ?>">
                                            <i class='bx <?= $post['saved'] ? 'bxs-bookmark' : 'bx-bookmark' ?>'></i>
                                        </button>
                                    </div>
                                    <div class="post-likes">
                                        <span class="likes-count"><?= $post['likes_count'] ?></span> curtidas
                                    </div>
                                    <div class="post-text">
                                        <a href="perfil.php?id=<?= $post['user_id'] ?>" class="post-username"><?= htmlspecialchars($post['username']) ?></a>
                                        <span class="post-description"><?= htmlspecialchars($post['description']) ?></span>
                                    </div>
                                    <div class="post-comments">
                                        <a href="#" class="view-comments">Ver todos os <?= $post['comments_count'] ?> comentários</a>
                                    </div>
                                    <div class="post-time">
                                        <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                                    </div>
                                </div>
                                <div class="post-add-comment">
                                    <input type="text" placeholder="Adicione um comentário..." class="comment-input">
                                    <button class="post-comment-btn">Publicar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-posts">
                            <i class='bx bx-ghost'></i>
                            <p>Nenhuma receita encontrada. Seja o primeiro a postar!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Coluna da direita -->
            <div class="coluna-direita">
                <div class="corpo-do-perfil">
                    <div class="miniatura-perfil2">
                        <img src="imgd/cheffeed.png" alt="Perfil">
                    </div>
                    <div>
                        <p class="nome-do-usuario">CHEF GAME ZONE</p>
                        <p class="sub-text">Jogos exclusivos para você!</p>
                    </div>
                    <a href="jogos.php" class="mudar-btn">EXPLORAR JOGOS</a>
                </div>

                <div class="suggestions-section">
                    <div class="suggestions-header">
                        <span>Sugestões para você</span>
                        <a href="#" class="see-all">Ver tudo</a>
                    </div>
                    <div class="suggestions-list">
                        <?php foreach ($suggestions as $suggestion):
                            $suggestionPic = !empty($suggestion['pp']) ? "../upload/" . htmlspecialchars($suggestion['pp']) : "imgf/default-profile.png";
                        ?>
                            <div class="suggestion-item">
                                <a href="perfil.php?id=<?= $suggestion['id'] ?>" class="suggestion-user">
                                    <img src="<?= $suggestionPic ?>" alt="Perfil" class="suggestion-pic">
                                    <div class="suggestion-info">
                                        <span class="suggestion-username"><?= htmlspecialchars($suggestion['username']) ?></span>
                                        <span class="suggestion-followers">Novo no Chef Feed</span>
                                    </div>
                                </a>
                                <button class="follow-btn" data-user-id="<?= $suggestion['id'] ?>">Seguir</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Configurações -->
        <div id="settings-modal" class="modal small-modal">
            <div class="modal-content">
                <span class="close-modal" onclick="document.getElementById('settings-modal').style.display='none'">&times;</span>
                <h2>Configurações</h2>
                <div class="setting-item">
                    <label class="switch">
                        <input type="checkbox" id="dark-mode-toggle">
                        <span class="slider round"></span>
                    </label>
                    <span>Modo Escuro</span>
                </div>
                <div class="setting-item" onclick="window.location.href='ajuda.php'">
                    <i data-lucide="help-circle"></i>
                    <span class="setting-text">Ajuda</span>
                </div>
                <div class="setting-item" onclick="window.location.href='denuncias.php'">
                    <i data-lucide="flag"></i>
                    <span class="setting-text">Minhas Denúncias</span>
                </div>
                <div class="setting-item" onclick="window.location.href='chat_suporte.php'">
                    <i data-lucide="message-square"></i>
                    <span class="setting-text">Suporte</span>
                </div>
                <?php if($is_admin): ?>
                <div class="setting-item" onclick="window.location.href='admin.php'">
                    <i data-lucide="shield"></i>
                    <span class="setting-text">Painel Admin</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal de Denúncia -->
        <div id="report-modal" class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="document.getElementById('report-modal').style.display='none'">&times;</span>
                <h2>Denunciar Postagem</h2>
                <form id="report-form">
                    <input type="hidden" id="report-post-id">
                    <div class="form-group">
                        <label for="report-reason">Motivo da denúncia:</label>
                        <select id="report-reason" class="form-input" required>
                            <option value="">Selecione um motivo</option>
                            <option value="spam">Spam</option>
                            <option value="inappropriate">Conteúdo inapropriado</option>
                            <option value="harassment">Assédio ou bullying</option>
                            <option value="violence">Violência ou discurso de ódio</option>
                            <option value="false_info">Informação falsa</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="report-details">Detalhes (opcional):</label>
                        <textarea id="report-details" class="form-input" rows="4"></textarea>
                    </div>
                    <button type="submit" class="login-button">Enviar Denúncia</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/home.js"></script>
</body>

</html>

<?php
} else {
    header("Location: login.php");
    exit;
}
?>
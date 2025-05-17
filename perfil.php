<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['fname'])) {
    header("Location: login.php");
    exit;
}

include "../db_conn.php";
include 'php/User.php';

// Obter ID do perfil a ser visualizado
$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['id'];

// Obter informações do usuário
$user = getUserById($profile_id, $conn);
if (!$user) {
    header("Location: home.php");
    exit;
}

// Verificar se o usuário logado é admin
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Verificar se é o próprio perfil
$is_own_profile = ($_SESSION['id'] == $profile_id);

// Obter estatísticas do perfil
$stats_stmt = $conn->prepare("
    SELECT 
        (SELECT COUNT(*) FROM posts WHERE user_id = ?) AS post_count,
        (SELECT COUNT(*) FROM follows WHERE following_id = ?) AS followers_count,
        (SELECT COUNT(*) FROM follows WHERE follower_id = ?) AS following_count
");
$stats_stmt->execute([$profile_id, $profile_id, $profile_id]);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se o usuário logado segue este perfil
$follow_stmt = $conn->prepare("
    SELECT 1 FROM follows 
    WHERE follower_id = ? AND following_id = ?
");
$follow_stmt->execute([$_SESSION['id'], $profile_id]);
$is_following = $follow_stmt->fetch();

// Obter posts do usuário
$posts_stmt = $conn->prepare("
    SELECT p.*, 
           (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS likes_count,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count
    FROM posts p
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$posts_stmt->execute([$profile_id]);
$posts = $posts_stmt->fetchAll(PDO::FETCH_ASSOC);

// Obter posts salvos (se for o próprio perfil)
$saved_posts = [];
if ($is_own_profile) {
    $saved_stmt = $conn->prepare("
        SELECT p.*, 
               (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS likes_count,
               (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comments_count
        FROM posts p
        JOIN saved_posts sp ON p.id = sp.post_id
        WHERE sp.user_id = ?
        ORDER BY sp.created_at DESC
    ");
    $saved_stmt->execute([$_SESSION['id']]);
    $saved_posts = $saved_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Determinar qual tab está ativa
$active_tab = isset($_GET['tab']) && $_GET['tab'] == 'saved' ? 'saved' : 'posts';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']) ?> - Chef Feed</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Estilos para a barra de tarefas */
        .sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 78px;
  background: var(--sidebar-light);
  padding: 6px 14px;
  transition: all 0.5s ease;
  z-index: 100;
}

.sidebar.active {
  width: 240px;
}

.sidebar .logo_content .logo {
  color: #000000;
  display: flex;
  height: 50px;
  width: 100%;
  align-items: center;
  opacity: 0;
  pointer-events: none;
  transition: all 0.5s ease;
}

.sidebar.active .logo_content .logo {
  opacity: 1;
  pointer-events: none;
}

.logo_content .logo .logo-icon {
  font-size: 28px;
  margin-right: 5px;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-icon img {
  width: auto;
  height: 100%;
  max-width: 100%;
  object-fit: contain;
  transition: all 0.5s ease;
}

.logo_content .logo .logo_name {
  font-size: 20px;
  font-weight: 400;
}

.sidebar #btn {
  position: absolute;
  color: #000000;
  left: 50%;
  top: 6px;
  font-size: 20px;
  height: 50px;
  width: 50px;
  text-align: center;
  line-height: 50px;
  transform: translateX(-50%);
  cursor: pointer;
}

.sidebar.active #btn {
  left: 90%;
}

.sidebar ul {
  margin-top: 20px;
}

.sidebar ul li {
  position: relative;
  height: 50px;
  width: 100%;
  margin: 0 5px;
  list-style: none;
  line-height: 50px;
}

.sidebar ul li .tooltip {
  position: absolute;
  left: 122px;
  top: 0;
  transform: translateY(-50%);
  border-radius: 6px;
  height: 35px;
  width: 122px;
  background: #fff;
  line-height: 35px;
  text-align: center;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  transition: 0s;
  opacity: 0;
  pointer-events: none;
  display: block;
  color: #11101d;
}

.sidebar.active ul li .tooltip {
  display: none;
}

.sidebar ul li:hover .tooltip {
  transition: all 0.5s ease;
  opacity: 1;
  top: 50%;
}

.sidebar ul li input {
  position: absolute;
  height: 100%;
  width: 100%;
  left: 0;
  top: 0;
  border-radius: 12px;
  outline: none;
  border: none;
  background: var(--sidebar-dark);
  padding-left: 50px;
  font-size: 15px;
  color: #fff;
}

.sidebar ul li i {
  color: #000000;
  height: 50px;
  min-width: 50px;
  border-radius: 12px;
  line-height: 50px;
  text-align: center;
  font-size: 18px;
}

.sidebar ul li span.links_name {
  color: #000000;
  opacity: 0;
  pointer-events: none;
  transition: all 0.5s ease;
}

.sidebar ul li a {
  text-decoration: none;
}

.sidebar.active ul li span.links_name {
  opacity: 1;
  pointer-events: auto;
}

.sidebar ul li:hover i,
.sidebar ul li:hover span.links_name {
  color: #e74c3c;
  background: #fff;
}

.sidebar .profile_content {
  position: absolute;
  color: #fff;
  bottom: 0;
  left: 0;
  width: 100%;
}

.sidebar .profile_content .profile {
  position: relative;
  padding: 10px 6px;
  height: 60px;
  transition: all 0.5s ease;
  background: none;
}

.sidebar.active .profile_content .profile {
  background: linear-gradient(to right, #ef4444, #f59e0b);
}

.sidebar .profile_content .profile .profile_details {
  display: flex;
  align-items: center;
  opacity: 0;
  pointer-events: none;
  white-space: nowrap;
}

.sidebar.active .profile .profile_details {
  opacity: 1;
  pointer-events: auto;
}

.profile .profile_details img {
  height: 45px;
  width: 45px;
  object-fit: cover;
  border-radius: 12px;
  margin-right: 10px;
}

.profile .profile_details .name_job {
  margin-left: 10px;
}

.profile .profile_details .name {
  font-size: 15px;
  font-weight: 400;
  color: #fff;
}

.profile .profile_details .job {
  font-size: 12px;
  color: #fff;
}

.profile #log_out {
  position: absolute;
  bottom: 5px;
  left: 50%;
  transform: translateX(-50%);
  min-width: 50px;
  line-height: 50px;
  font-size: 20px;
  border-radius: 12px;
  text-align: center;
  transition: all 0.4s ease;
  background: linear-gradient(to right, #ef4444, #f59e0b);
  color: #fff;
  cursor: pointer;
}

.sidebar.active .profile #log_out {
  left: 88%;
  background: none;
}
        
        /* Modal de edição de bio */
        .bio-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .bio-modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }
        
        .bio-modal textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px 0;
            min-height: 100px;
            resize: vertical;
        }
        
        .bio-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .bio-modal-actions button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .bio-modal-actions .save-btn {
            background: #4CAF50;
            color: white;
        }
        
        .bio-modal-actions .cancel-btn {
            background: #f5f5f5;
        }
        
        /* Ajustes de enquadramento */
        .home_content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .profile-header {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4CAF50;
        }
        
        .profile-info {
            flex: 1;
            min-width: 300px;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .links_name, .logo_name {
                display: none;
            }
            
            .home_content {
                margin-left: 70px;
            }
            
            .profile-header {
                justify-content: center;
                text-align: center;
            }
            
            .profile-actions {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="emoji-bg"></div>

    <!-- Barra Lateral Ajustada -->
    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <div class="logo-icon">
                    <img src="imgf/chapeu-de-chef.png" alt="Chef Feed" style="width: 40px; height: 40px;">
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
                    <img src="<?= $profilePic ?>" alt="Foto de perfil" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
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
            <div class="main-content profile-page">
                <!-- Cabeçalho do Perfil -->
                <div class="profile-container">
                    <div class="profile-header">
                        <img src="<?= $profilePic ?>" alt="Foto do perfil" class="profile-pic">
                        <div class="profile-info">
                            <div class="profile-top">
                                <h2><?= htmlspecialchars($user['username']) ?></h2>
                                <div class="profile-actions">
                                    <?php if ($is_own_profile): ?>
                                        <a href="edit.php" class="edit-profile">Editar perfil</a>
                                    <?php else: ?>
                                        <button class="follow-btn <?= $is_following ? 'following' : '' ?>" 
                                                data-user-id="<?= $profile_id ?>">
                                            <?= $is_following ? 'Seguindo' : 'Seguir' ?>
                                        </button>
                                        <button class="message-btn">Mensagem</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="profile-stats">
                                <span><b><?= $stats['post_count'] ?></b> publicações</span>
                                <span><b><?= $stats['followers_count'] ?></b> seguidores</span>
                                <span><b><?= $stats['following_count'] ?></b> seguindo</span>
                            </div>
                            <div class="profile-bio">
                                <p><?= htmlspecialchars($user['bio'] ?? '🍳 Dicas, truques e pratos deliciosos para todos os gostos') ?></p>
                                <?php if ($is_own_profile): ?>
                                <div class="bio-edit-container">
                                    <button class="edit-bio-btn" onclick="openBioEditor()">
                                        <i class='bx bx-edit'></i> Editar Bio
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="tabs">
                    <a href="?id=<?= $profile_id ?>&tab=posts" class="tab <?= $active_tab == 'posts' ? 'active' : '' ?>">
                        <i class='bx bx-grid-alt'></i> PUBLICAÇÕES
                    </a>
                    <?php if ($is_own_profile): ?>
                    <a href="?id=<?= $profile_id ?>&tab=saved" class="tab <?= $active_tab == 'saved' ? 'active' : '' ?>">
                        <i class='bx bx-bookmark'></i> SALVOS
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Grid de Fotos -->
                <div class="photo-grid">
                    <?php if ($active_tab == 'posts'): ?>
                        <?php if (!empty($posts)): ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="photo-item" onclick="window.location.href='post.php?id=<?= $post['id'] ?>'">
                                    <img src="<?= htmlspecialchars($post['photo_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                    <div class="photo-overlay">
                                        <div class="overlay-stats">
                                            <span>❤️ <?= $post['likes_count'] ?></span>
                                            <span>💬 <?= $post['comments_count'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-posts">
                                <i class='bx bx-ghost'></i>
                                <p>Nenhuma publicação encontrada</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if (!empty($saved_posts)): ?>
                            <?php foreach ($saved_posts as $post): ?>
                                <div class="photo-item" onclick="window.location.href='post.php?id=<?= $post['id'] ?>'">
                                    <img src="<?= htmlspecialchars($post['photo_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                                    <div class="photo-overlay">
                                        <div class="overlay-stats">
                                            <span>❤️ <?= $post['likes_count'] ?></span>
                                            <span>💬 <?= $post['comments_count'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-posts">
                                <i class='bx bx-bookmark'></i>
                                <p>Nenhum post salvo</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Bio -->
    <div id="bioModal" class="bio-modal">
        <div class="bio-modal-content">
            <h3>Editar Biografia</h3>
            <textarea id="bioText"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            <div class="bio-modal-actions">
                <button class="cancel-btn" onclick="closeBioEditor()">Cancelar</button>
                <button class="save-btn" onclick="saveBio()">Salvar</button>
            </div>
        </div>
    </div>

    <script src="js/home.js"></script>
    <script>
        // Ativar tooltips do Lucide
        lucide.createIcons();
        
        // Funções para edição de bio
        function openBioEditor() {
            document.getElementById('bioModal').style.display = 'flex';
        }
        
        function closeBioEditor() {
            document.getElementById('bioModal').style.display = 'none';
        }
        
        async function saveBio() {
            const newBio = document.getElementById('bioText').value;
            try {
                const response = await fetch('api/update_bio.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ bio: newBio })
                });
                
                if (response.ok) {
                    location.reload(); // Recarrega a página para mostrar a nova bio
                } else {
                    alert('Erro ao salvar a bio');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao conectar com o servidor');
            }
        }

        // Ativar funcionalidade de seguir
        document.querySelector('.follow-btn')?.addEventListener('click', async function() {
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
                        // Atualizar contador de seguidores
                        const followersCount = document.querySelector('.profile-stats span:nth-child(2) b');
                        followersCount.textContent = parseInt(followersCount.textContent) - 1;
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
                        // Atualizar contador de seguidores
                        const followersCount = document.querySelector('.profile-stats span:nth-child(2) b');
                        followersCount.textContent = parseInt(followersCount.textContent) + 1;
                    }
                } catch (error) {
                    console.error('Erro ao seguir:', error);
                }
            }
        });
    </script>
</body>
</html>
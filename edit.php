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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Editar Perfil</title>
        <link rel="stylesheet" href="css/editar-perfil.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <?php if ($user) { ?>
            <div class="container">
                <div class="header">
                    <h1>Editar Perfil</h1>
                </div>

                <div class="content-wrapper">
                    <!-- Menu Lateral -->
                    <div class="sidebar">
                        <ul class="nav-menu">
                            <li><a href="#info" class="active">Informações Básicas</a></li>
                            <li><a href="#change-password">Alterar Senha</a></li>
                        </ul>
                    </div>

                    <!-- Conteúdo Principal -->
                    <div class="main-content">
                        <!-- Aba Informações Básicas -->
                        <div id="info" class="tab-content active">

                            <form action="php/editar.php" method="post" enctype="multipart/form-data">
                                <!-- error -->
                                <?php if (isset($_GET['error'])) { ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?php echo $_GET['error']; ?>
                                    </div>
                                <?php } ?>

                                <!-- success -->
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $_GET['success']; ?>
                                    </div>
                                <?php } ?>

                                <div class="profile-section" style="display: flex; align-items: center; margin-bottom: 30px;">
                                    <img src="../upload/<?= $user['pp'] ?>" alt="Foto do perfil"
                                        style="width: 80px; height: 80px; border-radius: 50%; margin-right: 20px;">
                                    <div>
                                        <input type="file" class="form-control" name="pp" style="display: none;" id="profile-pic">
                                        <button type="button" class="btn btn-default" style="margin-right: 10px;" onclick="document.getElementById('profile-pic').click()">Alterar Foto</button>
                                        
                                        <p style="color: #777; font-size: 14px; margin-top: 5px;">Formatos permitidos: JPG, GIF ou PNG. Tamanho máximo: 800KB</p>
                                        <input type="text" hidden="hidden" name="old_pp" value="<?= $user['pp'] ?>" id="old-pp">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="fname">Nome Completo</label>
                                    <input type="text" id="fname" class="form-control" name="fname" value="<?php echo $user['fname'] ?>">
                                </div>

                                <div class="form-group">
                                    <label for="uname">Nome de Usuário</label>
                                    <input type="text" id="uname" class="form-control" name="uname" value="<?php echo $user['username'] ?>">
                                </div>

                                <div style="text-align: right; margin-top: 30px;">
                                    <a href="perfil.php" class="btn btn-default" style="margin-right: 10px;">Voltar</a>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                    <button type="button" name="delete_profile" class="btn btn-danger" style="margin-left: 10px;" onclick="confirmDelete()">Excluir Conta</button>
                                </div>
                            </form>
                        </div>

                        <!-- Aba Alterar Senha -->
                        <div id="change-password" class="tab-content">
                            <form action="php/change-password.php" method="post">
                                <?php
                                // Mostrar erros/sucesso específicos para a aba de senha
                                if (isset($_GET['tab']) && $_GET['tab'] == 'change-password') {
                                    if (isset($_GET['error'])) { ?>
                                        <div class="alert alert-warning" role="alert">
                                            <?php echo $_GET['error']; ?>
                                        </div>
                                    <?php }
                                    if (isset($_GET['success'])) { ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo $_GET['success']; ?>
                                        </div>
                                <?php }
                                } ?>

                                <div class="form-group">
                                    <label for="current-password">Senha Atual</label>
                                    <input type="password" id="current-password" name="current_password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="new-password">Nova Senha</label>
                                    <input type="password" id="new-password" name="new_password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="repeat-password">Repetir Nova Senha</label>
                                    <input type="password" id="repeat-password" name="confirm_password" class="form-control" required>
                                </div>

                                <div style="text-align: right; margin-top: 30px;">
                                    <button type="button" class="btn btn-default" style="margin-right: 10px;" onclick="switchTab('info')">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Alterar Senha</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function confirmDelete() {
                    if (confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
                        // Cria um formulário temporário para enviar a requisição de exclusão
                        const form = document.createElement('form');
                        form.method = 'post';
                        form.action = 'php/editar.php';

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_profile';
                        input.value = '1';

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                }

                function removeProfilePic() {
                    if (confirm('Deseja remover sua foto de perfil?')) {
                        document.getElementById('old-pp').value = 'default-pp.png';
                        window.location.href = 'php/editar.php?remove_pp=1';
                    }
                }

                // Função para alternar entre abas
                function switchTab(tabId) {
                    // Oculta todas as abas
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });

                    // Mostra a aba selecionada
                    document.getElementById(tabId).classList.add('active');

                    // Atualiza o menu ativo
                    document.querySelectorAll('.nav-menu a').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + tabId) {
                            link.classList.add('active');
                        }
                    });
                }

                // Configura os eventos de clique nos links do menu
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.nav-menu a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const tabId = this.getAttribute('href').substring(1);
                            switchTab(tabId);
                        });
                    });
                });
            </script>
        <?php } else {
            header("Location: home.php");
            exit;
        } ?>
    </body>

    </html>

<?php } else {
    header("Location: login.php");
    exit;
} ?>
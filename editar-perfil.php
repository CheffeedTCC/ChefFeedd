<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações de Conta</title>
    <link rel="stylesheet" href="css/editar-perfil.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Configurações da Conta</h1>
        </div>
        
        <div class="content-wrapper">
            <!-- Menu Lateral -->
            <div class="sidebar">
                <ul class="nav-menu">
                    <li><a href="#general" class="active">Geral</a></li>
                    <li><a href="#change-password">Alterar Senha</a></li>
                    <li><a href="#info">Informações</a></li>
                </ul>
            </div>
            
            <!-- Conteúdo Principal -->
            <div class="main-content">
                <!-- Aba Geral -->
                <div id="general" class="tab-content active">
                    <div class="profile-section" style="display: flex; align-items: center; margin-bottom: 30px;">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Foto do perfil" 
                             style="width: 80px; height: 80px; border-radius: 50%; margin-right: 20px;">
                        <div>
                            <button class="btn btn-default" style="margin-right: 10px;">Alterar Foto</button>
                            <p style="color: #777; font-size: 14px; margin-top: 5px;">Formatos permitidos: JPG, GIF ou PNG. Tamanho máximo: 800KB</p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Nome de Usuário</label>
                        <input type="text" id="username" class="form-control" value="nmaxwell">
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" class="form-control" value="Nelle Maxwell">
                    </div>
                    
                    
                    
                    <div style="text-align: right; margin-top: 30px;">
                        <button class="btn btn-default" style="margin-right: 10px;">Cancelar</button>
                        <button class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </div>
                
                <!-- Aba Alterar Senha -->
                <div id="change-password" class="tab-content">
                    <div class="form-group">
                        <label for="current-password">Senha Atual</label>
                        <input type="password" id="current-password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="new-password">Nova Senha</label>
                        <input type="password" id="new-password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="repeat-password">Repetir Nova Senha</label>
                        <input type="password" id="repeat-password" class="form-control">
                    </div>
                    
                    <div style="text-align: right; margin-top: 30px;">
                        <button class="btn btn-default" style="margin-right: 10px;">Cancelar</button>
                        <button class="btn btn-primary">Alterar Senha</button>
                    </div>
                </div>
                
                <!-- Outras abas podem ser adicionadas aqui -->
                <div id="info" class="tab-content">
                    <h2>Informações Pessoais</h2>
                    <p>Conteúdo da aba de informações...</p>
                </div>
                
                <div id="social-links" class="tab-content">
                    <h2>Redes Sociais</h2>
                    <p>Conteúdo da aba de redes sociais...</p>
                </div>
                
                <div id="connections" class="tab-content">
                    <h2>Conexões</h2>
                    <p>Conteúdo da aba de conexões...</p>
                </div>
                
                <div id="notifications" class="tab-content">
                    <h2>Notificações</h2>
                    <p>Conteúdo da aba de notificações...</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleciona todos os links do menu
            const menuLinks = document.querySelectorAll('.nav-menu a');
            
            // Adiciona evento de clique a cada link
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove a classe 'active' de todos os links
                    menuLinks.forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Adiciona a classe 'active' apenas ao link clicado
                    this.classList.add('active');
                    
                    // Oculta todos os conteúdos das abas
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    
                    // Mostra o conteúdo da aba correspondente
                    const targetId = this.getAttribute('href');
                    document.querySelector(targetId).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
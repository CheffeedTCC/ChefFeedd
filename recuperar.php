<?php
session_start();

// Configuração do banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cheffeed';

// Conexão com MySQLi
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $mysqli->real_escape_string($_POST['email']);

    // Verificar se email existe
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $mysqli->query($sql);

    if ($result->num_rows === 0) {
        $erro = "Email não cadastrado no sistema";
    } else {
        // Gerar nova senha
        $nova_senha = substr(md5(uniqid(rand(), true)), 0, 8);
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualizar banco de dados
        $update_sql = "UPDATE users SET password = '$senha_hash' WHERE email = '$email'";

        if ($mysqli->query($update_sql)) {
            // Simulação de envio de email (em desenvolvimento mostra na tela)
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
                $sucesso = "SENHA TEMPORÁRIA (Voce poderá mudar a senha na ediçao de perfil): $nova_senha";
            } else {
                // Código para produção que envia email de verdade
                $assunto = "Nova Senha - Chef Feed";
                $mensagem = "Sua nova senha temporária é: $nova_senha\n\n";
                $mensagem .= "Recomendamos que altere esta senha após o login.";

                if (mail($email, $assunto, $mensagem)) {
                    $sucesso = "Nova senha enviada para seu email";
                } else {
                    $erro = "Erro ao enviar email com a nova senha";
                }
            }
        } else {
            $erro = "Erro ao atualizar senha: " . $mysqli->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Chef Feed</title>
    <link rel="stylesheet" href="css/recuperar.css">
</head>

<body>
    <div class="container">
        <div class="card">

            <div class="icon-container">
                <svg aria-label="Problemas para entrar?" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor"
                    height="96" role="img" viewBox="0 0 96 96" width="96">
                    <title>Problemas para entrar?</title>
                    <circle cx="48" cy="48" fill="none" r="47" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2"></circle>
                    <path
                        d="M60.931 70.001H35.065a5.036 5.036 0 0 1-5.068-5.004V46.005A5.036 5.036 0 0 1 35.065 41H60.93a5.035 5.035 0 0 1 5.066 5.004v18.992A5.035 5.035 0 0 1 60.93 70ZM37.999 39.996v-6.998a10 10 0 0 1 20 0v6.998"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"></path>
                </svg>
            </div>    
                <h2>Recuperar Senha</h2>

                <p>Digite seu E-mail para receber a senha temporaria</p>
                <p>você poderá alterar sua senha mais tarde</p>

                <?php if (!empty($erro)): ?>
                    <div class="error"><?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>

                <?php if (!empty($sucesso)): ?>
                    <div class="success"><?php echo htmlspecialchars($sucesso); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Digite seu E-mail cadastrado" required>
                    </div>
                    <button type="submit">Gerar Nova Senha</button>
                </form>

                <div class="login-link">
                    <a href="login.php">Voltar para o Login</a>
                </div>
            </div>
        </div>
</body>

</html>
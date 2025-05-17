<?php
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pp'])) {
        include __DIR__ . '/../db_conn.php';

        if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
            $img_name = $_FILES['pp']['name'];
            $tmp_name = $_FILES['pp']['tmp_name'];
            $error = $_FILES['pp']['error'];

            if ($error === 0) {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_to_lc = strtolower($img_ex);

                $allowed_exs = array('jpg', 'jpeg', 'png');
                if (in_array($img_ex_to_lc, $allowed_exs)) {
                    // Pasta de upload
                    $upload_dir = __DIR__ . '/../upload/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Gera o novo nome do arquivo
                    $sql = "SELECT username FROM users WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();

                    if ($user) {
                        $new_img_name = uniqid($user['username'], true) . '.' . $img_ex_to_lc;
                        $img_upload_path = $upload_dir . $new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);

                        // Atualiza o banco de dados
                        $sql = "UPDATE users SET pp=? WHERE id=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$new_img_name, $user_id]);

                        header("Location: home.php?success=Foto de perfil adicionada com sucesso");
                        exit;
                    } else {
                        $error = "Usuário não encontrado.";
                    }
                } else {
                    $error = "Você só pode enviar arquivos do tipo jpg, jpeg ou png.";
                }
            } else {
                $error = "Ocorreu um erro ao enviar a imagem.";
            }
        } else {
            $error = "Por favor, selecione uma imagem.";
        }
    } elseif (isset($_POST['skip'])) {
        // Caso o usuário escolha pular
        header("Location: ../login.php?success=Cadastro concluído sem foto de perfil");
        exit;
    }
} else {
    header("Location: cadastro.php?error=Erro");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastro.css">
    <title>Selecionar Foto</title>
    <style>
        #preview {
            display: block;
            margin: 20px auto 0 auto;
            max-width: 300px;
            max-height: 300px;
            object-fit: cover;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 50%;
        }

        .btn-skip {
            background: linear-gradient(to right, #ef4444, #f59e0b);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            color: #ffffff;
            cursor: pointer;
            width: 100%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn-skip:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1 class="form-title">Selecionar Foto de Perfil</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="selecionar_foto.php?user_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <div class="input-box">
                <label class="form-label">Escolha uma foto de perfil</label>

                <div>
                    <img id="preview" src="#" alt="Pré-visualização da imagem" style="display: none;">
                </div>

                <div class="input-file">
                    <input type="file" id="fotoInput" class="form-control" name="pp" required>
                </div>
            </div>

            <div class="buttons-container">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-upload"></i> Enviar Foto
                </button>
            </div>
        </form>

        <a href="login.php?success=Cadastro concluído sem foto de perfil" class="btn-skip">
            <i class="fas fa-forward"></i> Pular e ir para Login
        </a>
    </div>

    <script>
        const input = document.getElementById('fotoInput');
        const preview = document.getElementById('preview');

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
        $_SESSION['profile_image'] = $uploadFile;
        $message = "Upload realizado com sucesso!";
    } else {
        $message = "Erro no upload da imagem.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Imagem</title>
    <link rel="stylesheet" href="css/upload-imagem.css">
</head>
<body>
    <div class="hero">
        <div class="card">
            <h1>Selecionar foto do perfil</h1>
            <img src="<?php echo $_SESSION['profile_image'] ?? 'img/profile.png'; ?>" id="profile-pic">
            
            <?php if (!empty($message)): ?>
                <p class="message"> <?php echo htmlspecialchars($message); ?> </p>
            <?php endif; ?>

            <form action="upload-imagem.php" method="POST" enctype="multipart/form-data">
            
                <label for="input-file">Atualizar Imagem</label>
                <input type="file" accept="image/jpeg, image/png, image/jpg" id="input-file" name="profile_image" required>
               
                <button type="submit" class="concluir-btn">Enviar</button>
            </form>


            
        </div>
    </div>

    <script>
        let profilePic = document.getElementById("profile-pic");
        let inputFile = document.getElementById("input-file");

        inputFile.onchange = function(){
            profilePic.src = URL.createObjectURL(inputFile.files[0]);
        }
    </script>
</body>
</html>

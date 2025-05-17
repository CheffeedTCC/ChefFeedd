<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Criar Conta</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="php/signup.php" method="POST">
            <div class="input-box">
                <label class="form-label">Nome Completo*</label>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" name="fname" placeholder="fulano da silva" 
                           value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : '' ?>" required>
                </div>
            </div>

            <div class="input-box">
                <label class="form-label">Data de Nascimento*</label>
                <div class="input-field">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" class="form-control" name="nasc" 
                           value="<?php echo isset($_GET['nasc']) ? htmlspecialchars($_GET['nasc']) : '' ?>" required>
                </div>
            </div>

            <div class="input-box">
                <label class="form-label">Email*</label>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" name="email" placeholder="fulano@gmail.com" 
                           value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>" required>
                </div>
            </div>

            <div class="input-box">
                <label class="form-label">Usuário*</label>
                <div class="input-field">
                    <i class="fas fa-at"></i>
                    <input type="text" class="form-control" name="uname" placeholder="fulanim01_" 
                           value="<?php echo isset($_GET['uname']) ? htmlspecialchars($_GET['uname']) : '' ?>" required>
                </div>
            </div>

            <div class="input-box">
                <label class="form-label">Senha*</label>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" name="pass" placeholder="••••••••" required>
                </div>
            </div>

            <div class="input-box">
                <label class="form-label">Gênero*</label>
                <div class="gender-options">
                    <div class="gender-option">
                        <input type="radio" id="female" name="gender" value="female" 
                               <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'female') ? 'checked' : '' ?> required>
                        <label for="female" class="gender-label">
                            <i class="fas fa-venus"></i> Feminino
                        </label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" id="male" name="gender" value="male" 
                               <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'male') ? 'checked' : '' ?>>
                        <label for="male" class="gender-label">
                            <i class="fas fa-mars"></i> Masculino
                        </label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" id="other" name="gender" value="other" 
                               <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'other') ? 'checked' : '' ?>>
                        <label for="other" class="gender-label">
                            <i class="fas fa-genderless"></i> Outro
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> Cadastrar
            </button>

            <div class="login-link">
                <span class="login-text">Já possui uma conta? <a href="login.php" class="login-anchor">Login</a></span>
            </div>
        </form>
    </div>
</body>
</html>
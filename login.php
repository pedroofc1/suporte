<?php
session_start();
require 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    // Se quiser manter MD5, use:
    $password = md5($_POST['pass']);

    
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE login = ? AND pass = ?");
    $stmt->execute([$login, $password]);
    $user = $stmt->fetch();

    if ($user) {
        // Armazena dados do usuário na sessão
        $_SESSION['user'] = $user;

        // Verifica nível do usuário e redireciona
        switch ($user['nivel']) {
            case 'administrador':
                header("Location: dashboard_admin.php");
                break;
                
            case 'utilizador':
                // Exemplo: pode direcionar para um dashboard de usuário comum
                header("Location: dashboard_utilizador.php");
                break;

            case 'Deputado':
                // Exemplo: caso exista um dashboard específico
                header("Location: dashboard_deputado.php");
                break;

            default:
                // Caso apareça outro nível
                $error = "Nível de acesso desconhecido!";
        }
        exit;
    } else {
        $error = "Credenciais inválidas!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - JSD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FF8C00;
            background: url("frutigerfundo.jpg") no-repeat center center fixed; 
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .error-message {
            color: #dc2626;
            margin-bottom: 15px;
            text-align: center;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.8);
            border-bottom: 2px solid #FF8C00;
        }
        .navbar-brand img {
            max-height: 90px;
        }
        .logout-btn {
            background-color: red;
            color: white;
            border-radius: 5px;
            padding: 10px 15px;
        }
        @media (max-width: 768px) {
            .login-container {
                margin-top: 150px;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-light fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">
                    <img src="jsdlogin.png" alt="Logotipo JSD">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="nav-item">
                                <a class="nav-link logout-btn" href="index.html">Sair</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="login-container">
        <h1>Aderir - JSD</h1>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="login" placeholder="Utilizador" required class="form-control mb-3">
            <input type="password" name="pass" placeholder="Senha" required class="form-control mb-3">
            <button type="submit" class="btn btn-warning w-100">Entrar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

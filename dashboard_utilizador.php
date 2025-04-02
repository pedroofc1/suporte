<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verifica se o usuário tem nível "utilizador". Caso não tenha, redireciona para outra página.
if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'utilizador') {
    header("Location: acesso_negado.php");
    exit;
}

// Conexão ao banco de dados (caso não esteja no arquivo 'conexao.php')
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Exemplo: recuperar dados do usuário logado (supondo que o ID esteja em $_SESSION['Cod_militante'])
$usuarioID = $_SESSION['id'];
$sql = "SELECT * FROM utilizadores WHERE id = $usuarioID";
$result = $conn->query($sql);
$usuario = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Utilizador - Portal JSD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Navbar */
    .navbar-custom { background-color: orange; }
    .navbar-custom .nav-link { color: white; }
    
    /* Icon-box para menu (opcional) */
    .icon-box {
      text-align: center;
      padding: 15px;
      background: #e3f2fd;
      border-radius: 20px;
      transition: 0.3s;
      margin-bottom: 15px;
    }
    .icon-box:hover { background: #bbdefb; }
    
    /* Estilo do container de perfil */
    .perfil-container {
      margin-top: 40px;
    }
    .perfil-card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .perfil-card h3 { color: #333; }
    .perfil-card p { font-size: 1.1rem; }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard_utilizador.php">Portal JSD</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active">Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?></a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Menu (opcional, exemplo com icon-box) -->
  <div class="container mt-4">
    <div class="row justify-content-center">
      <!-- Exemplo de menu com dois itens -->
      <div class="col-md-3">
        <div class="icon-box">
          <img src="perfil.png" width="45" alt="Perfil">
          <a href="dashboard_usuario.php" style="text-decoration: none; color: #333;"><span>Meu Perfil</span></a>
        </div>
      </div>
      <div class="col-md-3">
        <div class="icon-box">
          <img src="configuracao.png" width="45" alt="Configurações">
          <a href="editar_perfil.php" style="text-decoration: none; color: #333;"><span>Configurações</span></a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Conteúdo do Dashboard de Usuário -->
  <div class="container perfil-container">
    <div class="perfil-card">
      <h3>Meu Perfil</h3>
      <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
      <p><strong>Login:</strong> <?php echo htmlspecialchars($usuario['login']); ?></p>
      <p><strong>Status:</strong> <?php echo ($usuario['status'] == 'ativo') ? 'Ativo' : 'Inativo'; ?></p>
      <p><strong>Nível:</strong> <?php echo htmlspecialchars($usuario['nivel']); ?></p>
      <a class="btn btn-primary" href="editar_perfil.php">Editar Perfil</a>
    </div>
  </div>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

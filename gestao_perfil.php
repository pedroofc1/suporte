<?php

session_start();
include('conexao.php'); // Arquivo de conexão com o banco

if (!isset($_SESSION['user_id'])) {
    header('Location: gestao_perfil.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT nome, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Perfil</title>
</head>
<body>
    <h2>Gerenciar Perfil</h2>
    <form action="atualizar_perfil.php" method="post">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>
        <label>Nova Senha:</label>
        <input type="password" name="senha">
        <br>
        <button type="submit">Atualizar</button>
    </form>
</body>
</html>

<?php // atualizar_perfil.php
session_start();
include('conexao.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

if (!empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET nome = ?, email = ?, senha = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $email, $senha_hash, $user_id);
} else {
    $sql = "UPDATE users SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $email, $user_id);
}

if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso.";
} else {
    echo "Erro ao atualizar perfil.";
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Portal JSD - Gestão de Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar-custom { background-color: orange; }
    .navbar-custom .nav-link { color: white; }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Portal JSD</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active">Bem-vindo, <?php echo $militante['nome']; ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Conteúdo Principal -->
  <div class="container mt-5">
    <h3>Gestão de Perfil</h3>
    <hr>
    <div class="card">
      <div class="card-header">
        <h5>Dados do Perfil</h5>
      </div>
      <div class="card-body">
        <p><strong>ID:</strong> <?php echo $militante['Cod_militante']; ?></p>
        <p><strong>Nome:</strong> <?php echo $militante['nome']; ?></p>
        <p><strong>Login:</strong> <?php echo $militante['login']; ?></p>
        <p><strong>Status:</strong> <?php echo ($militante['status'] ? 'Ativo' : 'Inativo'); ?></p>
        <p><strong>Nível:</strong> <?php echo $militante['nivel']; ?></p>
        <!-- Exiba outros dados do perfil conforme necessário -->
        <button type="button" class="btn btn-primary btn-modal" 
                data-id="<?php echo $militante['Cod_militante']; ?>" 
                data-action="editar">
          Editar Perfil
        </button>
      </div>
    </div>
  </div>
  
  <!-- Modal Genérico -->
  <div class="modal fade" id="genericModal" tabindex="-1" aria-labelledby="genericModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="genericModalLabel">Editar Perfil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <!-- Conteúdo carregado via AJAX (ex.: modal_content.php?tipo=perfil&id=...) -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" id="btn-save">Salvar mudanças</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const genericModalEl = document.getElementById('genericModal');
      const genericModal = new bootstrap.Modal(genericModalEl);
  
      // Abre o modal para editar o perfil
      document.querySelector('.btn-modal').addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const action = this.getAttribute('data-action');
          
          // Requisição AJAX para carregar o formulário de edição do perfil
          fetch(`modal_content.php?id=${id}&action=${action}&tipo=perfil`)
            .then(response => response.text())
            .then(data => {
              genericModalEl.querySelector('.modal-body').innerHTML = data;
              genericModal.show();
            })
            .catch(error => console.error('Erro ao carregar modal:', error));
      });
  
      // Salva as alterações do perfil
      document.getElementById('btn-save').addEventListener('click', function() {
        const form = genericModalEl.querySelector('form');
        if (form) {
          const formData = new FormData(form);
          fetch(form.action, {
            method: form.method,
            body: formData
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              genericModal.hide();
              location.reload();
            } else {
              genericModalEl.querySelector('.modal-body').innerHTML = result.html;
            }
          })
          .catch(error => console.error('Erro ao salvar mudanças:', error));
        }
      });
    });
  </script>
</body>
</html>

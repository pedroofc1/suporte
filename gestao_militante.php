<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Conexão ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// CSS customizado para botões, páginas de mensagem, navbar e menu
$customCSS = '
<style>
:root {
    --btn-editar-bg: rgb(255, 153, 0);
    --btn-editar-bg-hover: rgb(255, 123, 0);
    --btn-editar-color: #ffffff;
    
    --btn-apagar-bg: #dc3545;
    --btn-apagar-bg-hover: #c82333;
    --btn-apagar-color: #ffffff;
    
    --btn-salvar-bg: rgb(255, 153, 0);
    --btn-salvar-bg-hover: rgb(255, 123, 0);
    --btn-salvar-color: #ffffff;
}

/* Botões de edição, apagar e salvar */
.btn-editar {
    background-color: var(--btn-editar-bg) !important;
    color: var(--btn-editar-color) !important;
    border: none !important;
}
.btn-editar:hover {
    background-color: var(--btn-editar-bg-hover) !important;
}

.btn-apagar {
    background-color: var(--btn-apagar-bg) !important;
    color: var(--btn-apagar-color) !important;
    border: none !important;
}
.btn-apagar:hover {
    background-color: var(--btn-apagar-bg-hover) !important;
}

.btn-salvar {
    background-color: var(--btn-salvar-bg) !important;
    color: var(--btn-salvar-color) !important;
    border: none !important;
}
.btn-salvar:hover {
    background-color: var(--btn-salvar-bg-hover) !important;
}

/* Botão para inserir utilizador */
.btn-inserir-militante {
    background-color: var(--btn-salvar-bg);
    color: var(--btn-salvar-color);
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn-inserir-militante:hover {
    background-color: var(--btn-salvar-bg-hover);
}

/* Estilos para as páginas de mensagem (sucesso/erro) */
body.msg-body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
  background: #f7f7f7;
  font-family: Arial, sans-serif;
}
.msg-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 20px;
}
.msg-card {
  background: #ffffff;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 40px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  text-align: center;
}
.msg-card h1 {
  font-size: 1.8rem;
  margin-bottom: 20px;
  color: #ff6600;
}
.msg-card p {
  font-size: 1rem;
  margin-bottom: 20px;
  color: #333;
}
a.btn-msg {
  display: inline-block;
  background: #ff6600;
  color: #fff;
  text-decoration: none;
  padding: 10px 20px;
  border-radius: 5px;
  transition: background 0.3s ease;
}
a.btn-msg:hover {
  background: #e65c00;
}

/* Estilos para a Navbar e Menu */
.navbar-custom { background-color: orange; }
.navbar-custom .nav-link { color: white; }

/* Fundo das seções (icon-box) igual ao das cotas */
.icon-box {
  text-align: center;
  padding: 15px;
  background: #e3f2fd;
  border-radius: 20px;
  transition: 0.3s;
  margin-bottom: 15px;
}
.icon-box:hover {
  background: #bbdefb;
}

/* Ajuste para a coluna de Ações, aumentando a caixa */
th.acao-col {
  min-width: 160px; /* Ajuste conforme necessário */
}
</style>
';

// ============================================================================
// 1. INSERIR
// ============================================================================
if ($acao === 'inserir') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome   = $_POST['nome'];
        $login  = $_POST['login'];
        $pass   = $_POST['pass'];
        $status = $_POST['status'];
        $nivel  = $_POST['nivel'];

        // Agora na TABELA 'utilizadores'
        $sql = "INSERT INTO utilizadores (nome, login, pass, status, nivel)
                VALUES ('$nome', '$login', '$pass', '$status', '$nivel')";
        if ($conn->query($sql) === TRUE) {
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Utilizador Inserido - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Utilizador inserido com sucesso!</h1>
                  <p>O novo utilizador foi registrado na base de dados.</p>
                  <a href="gestao_utilizadores.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        } else {
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Erro ao Inserir - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Erro ao inserir!</h1>
                  <p>Ocorreu um erro ao inserir o utilizador: ' . $conn->error . '</p>
                  <a href="gestao_utilizadores.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        }
        exit;
    } else {
        // Exibe o formulário de inserção com Bootstrap e CSS customizado
        ?>
        <!DOCTYPE html>
        <html lang="pt">
        <head>
          <meta charset="UTF-8">
          <title>Inserir Novo Utilizador - Portal JSD</title>
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- Bootstrap CSS -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
          <?php echo $customCSS; ?>
        </head>
        <body>
          <!-- Navbar -->
          <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
              <a class="navbar-brand" href="dashboard_admin.php">Portal JSD</a>
              <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                  <li class="nav-item"><a class="nav-link active">Bem-vindo, Administrador</a></li>
                  <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
              </div>
            </div>
          </nav>
          
          <div class="container mt-5">
            <h2>Inserir Novo Utilizador</h2>
            <form action="?acao=inserir" method="post">
              <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
              </div>
              <div class="mb-3">
                <label for="login" class="form-label">Login:</label>
                <input type="text" class="form-control" id="login" name="login" required>
              </div>
              <div class="mb-3">
                <label for="pass" class="form-label">Password:</label>
                <input type="password" class="form-control" id="pass" name="pass" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select name="status" id="status" class="form-select">
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="nivel" class="form-label">Nível:</label>
                <select name="nivel" id="nivel" class="form-select">
                  <option value="administrador">Administrador</option>
                  <option value="utilizador">Utilizador</option>
                  <option value="tecnico">Técnico</option>
                </select>
              </div>
              <button type="submit" class="btn btn-salvar">Salvar</button>
              <a href="gestao_militante.php" class="btn btn-secondary">Voltar</a>
            </form>
          </div>
          
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
        exit;
    }
}

// ============================================================================
// 2. EDITAR
// ============================================================================
if ($acao === 'editar') {
    if (!isset($_GET['id'])) {
        echo '<div class="container mt-5"><div class="alert alert-danger">ID não especificado.</div></div>';
        exit;
    }
    $id = intval($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome   = $_POST['nome'];
        $login  = $_POST['login'];
        $pass   = $_POST['pass'];
        $status = $_POST['status'];
        $nivel  = $_POST['nivel'];

        $sql = "UPDATE utilizadores 
                SET nome='$nome',
                    login='$login',
                    pass='$pass',
                    status='$status',
                    nivel='$nivel'
                WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Utilizador Atualizado - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Utilizador atualizado com sucesso!</h1>
                  <p>O registro do utilizador foi atualizado na base de dados.</p>
                  <a href="gestao_utilizadores.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        } else {
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Erro ao Atualizar - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Erro ao atualizar!</h1>
                  <p>Ocorreu um erro ao atualizar o registro: ' . $conn->error . '</p>
                  <a href="gestao_utilizadores.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        }
        exit;
    } else {
        // Busca o registro para preencher o formulário
        $sql   = "SELECT * FROM utilizadores WHERE id=$id";
        $query = $conn->query($sql);
        if ($query && $query->num_rows > 0) {
            $row = $query->fetch_assoc();
            ?>
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Editar Utilizador - Portal JSD</title>
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              <?php echo $customCSS; ?>
              <style>
                .navbar-custom { background-color: orange; }
                .navbar-custom .nav-link { color: white; }
              </style>
            </head>
            <body>
              <!-- Navbar -->
              <nav class="navbar navbar-expand-lg navbar-custom">
                <div class="container-fluid">
                  <a class="navbar-brand" href="dashboard_admin.php">Portal JSD</a>
                  <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                      <li class="nav-item"><a class="nav-link active">Bem-vindo, Administrador</a></li>
                      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                  </div>
                </div>
              </nav>
              
              <div class="container mt-5">
                <h2>Editar Utilizador</h2>
                <form action="?acao=editar&id=<?php echo $id; ?>" method="post">
                  <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="<?php echo $row['nome']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="login" class="form-label">Login:</label>
                    <input type="text" class="form-control" id="login" name="login"
                           value="<?php echo $row['login']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="pass" class="form-label">Password:</label>
                    <input type="text" class="form-control" id="pass" name="pass"
                           value="<?php echo $row['pass']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" id="status" class="form-select">
                      <option value="ativo"   <?php echo ($row['status'] == 'ativo')   ? 'selected' : ''; ?>>Ativo</option>
                      <option value="inativo" <?php echo ($row['status'] == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="nivel" class="form-label">Nível:</label>
                    <select name="nivel" id="nivel" class="form-select">
                      <option value="administrador" <?php echo ($row['nivel'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                      <option value="utilizador" <?php echo ($row['nivel'] == 'utilizador') ? 'selected' : ''; ?>>Utilizador</option>
                      <option value="tecnico" <?php echo ($row['nivel'] == 'tecnico') ? 'selected' : ''; ?>>Técnico</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-salvar">Salvar Alterações</button>
                  <a href="gestao_militante.php" class="btn btn-secondary">Voltar</a>
                </form>
              </div>
              
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            </body>
            </html>
            <?php
        } else {
            echo '<div class="container mt-5"><div class="alert alert-danger">Registro não encontrado.</div></div>';
        }
        exit;
    }
}

// ============================================================================
// 3. APAGAR
// ============================================================================
if ($acao === 'apagar') {
    if (!isset($_GET['id'])) {
        echo '<div class="container mt-5"><div class="alert alert-danger">ID não especificado.</div></div>';
        exit;
    }
    $id = intval($_GET['id']);
    $sql = "DELETE FROM utilizadores WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo '
        <!DOCTYPE html>
        <html lang="pt">
        <head>
          <meta charset="UTF-8">
          <title>Utilizador Apagado - Portal JSD</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
          ' . $customCSS . '
          <style>
            html, body {
              margin: 0;
              padding: 0;
              height: 100%;
              font-family: Arial, sans-serif;
              background: url("jsd_background.jpg") no-repeat center center;
              background-size: cover;
            }
            .message-container {
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
              height: 100%;
              text-align: center;
              background-color: rgba(255, 255, 255, 0.8);
            }
            .message-container .alert {
              font-size: 24px;
              font-weight: bold;
              color: orange;
            }
            .message-container a.btn {
              margin-top: 20px;
              font-size: 18px;
              padding: 10px 20px;
              background-color: orange;
              color: white;
              border: none;
              text-decoration: none;
            }
          </style>
        </head>
        <body>
          <div class="message-container">
            <div class="alert alert-success">Utilizador apagado com sucesso!</div>
            <a class="btn btn-primary" href="gestao_militante.php">Voltar à listagem</a>
          </div>
        </body>
        </html>';
    } else {
        echo '<div class="container mt-5">
                <div class="alert alert-danger">Erro ao apagar: ' . $conn->error . '</div>
                <a class="btn btn-primary" href="gestao_militante.php">Voltar à listagem</a>
              </div>';
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Utilizadores - Portal JSD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php echo $customCSS; ?>
  <style>
    .navbar-custom { background-color: orange; }
    .navbar-custom .nav-link { color: white; }

    /* Fundo das seções igual ao das cotas */
    .icon-box {
      text-align: center;
      padding: 15px;
      background: #e3f2fd;
      border-radius: 20px;
      transition: 0.3s;
      margin-bottom: 15px;
    }
    .icon-box:hover {
      background: #bbdefb;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard_admin.php">Portal JSD</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active">Bem-vindo, Administrador</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Menu (igual ao das cotas) -->
  <div class="container mt-4">
    <div class="row">
      <!-- Primeira linha: 3 colunas -->
      <div class="col-md-4">
        <div class="icon-box">
          <img src="logoicon.png" width="45" alt="Militantes">
          <a href="gestao_militante.php">Gestão de Militantes</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="icon-box">
          <img src="concelhias.png" width="30" alt="Concelhias">
          <a href="gestao_concelhias.php">Gestão de Concelhias</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="icon-box">
          <img src="eventos.png" width="45" alt="Eventos">
          <a href="gestao_eventos.php">Gestão de Eventos</a>
        </div>
      </div>
    </div>
    <!-- Segunda linha: 2 colunas com offset para centralizar -->
    <div class="row">
      <div class="col-md-4 offset-md-2 mt-3">
        <div class="icon-box">
          <img src="cotas.png" width="50" alt="Cotas">
          <a href="gestao_cotas.php">Gestão de Cotas</a>
        </div>
      </div>
      <div class="col-md-4 mt-3">
        <div class="icon-box">
          <img src="logoescri.png" width="50" alt="Escritórios">
          <a href="gestao_escritorios.php">Gestão de Escritórios</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Listagem de Utilizadores -->
  <div class="container mt-5">
    <h1>Gestão de Utilizadores</h1>
    <p><a class="btn btn-success" href="?acao=inserir">+ Inserir novo utilizador</a></p>
    <div class="card">
      <div class="card-body">
        <!-- Tabela com classe table-responsive (caso queira rolagem horizontal) -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Login</th>
                <th>Password</th>
                <th>Status</th>
                <th>Nível</th>
                <th class="acao-col">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql   = "SELECT * FROM utilizadores ORDER BY id ASC";
              $query = $conn->query($sql);
              if (!$query) {
                  echo "<tr><td colspan='7'>Erro na consulta: " . $conn->error . "</td></tr>";
              } else {
                  if ($query->num_rows > 0) {
                      while ($row = $query->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . $row['id'] . "</td>";
                          echo "<td>" . $row['nome'] . "</td>";
                          echo "<td>" . $row['login'] . "</td>";
                          echo "<td>" . $row['pass'] . "</td>";
                          echo "<td>" . $row['status'] . "</td>";
                          echo "<td>" . $row['nivel'] . "</td>";
                          echo "<td>
                                  <a class='btn btn-editar btn-sm me-2'
                                     href='?acao=editar&id=" . $row['id'] . "'>Editar</a>
                                  <a class='btn btn-apagar btn-sm'
                                     href='?acao=apagar&id=" . $row['id'] . "'
                                     onclick=\"return confirm('Tem a certeza que deseja apagar este utilizador?');\">
                                     Apagar
                                  </a>
                                </td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='7'>Não há utilizadores cadastrados.</td></tr>";
                  }
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

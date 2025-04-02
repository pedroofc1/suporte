<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Conexão ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// CSS customizado para os botões, páginas de mensagem, navbar e menu
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

/* Botões de edição, apagar e salvar (NÃO MEXIDO) */
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

/* Botão para inserir concelhia (NÃO MEXIDO) */
.btn-inserir-concelhia {
    background-color: rgb(255, 153, 0);
    color: #ffffff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn-inserir-concelhia:hover {
    background-color: rgb(255, 123, 0);
}

/* Estilos para as páginas de mensagem (sucesso/erro) (NÃO MEXIDO) */
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
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
</style>
';

// Captura a ação (inserir, editar, apagar)
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

/* ==========================
   1. INSERIR
   ========================== */
if ($acao === 'inserir') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome_concelhia = $_POST['Nome_Concelhia'];
        $distrito       = $_POST['Distrito'];
        $estado         = $_POST['Estado'];

        $sql = "INSERT INTO concelhias (Nome_Concelhia, Distrito, Estado)
                VALUES ('$nome_concelhia', '$distrito', '$estado')";
        if ($conn->query($sql) === TRUE) {
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Registro Inserido - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Registro inserido com sucesso!</h1>
                  <p>A nova concelhia foi registrada na base de dados.</p>
                  <a href="gestao_concelhias.php" class="btn-msg">Voltar à listagem</a>
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
                  <p>Ocorreu um erro ao inserir o registro: ' . $conn->error . '</p>
                  <a href="gestao_concelhias.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        }
        exit;
    } else {
        // Formulário de inserção
        ?>
        <!DOCTYPE html>
        <html lang="pt">
        <head>
          <meta charset="UTF-8">
          <title>Inserir Nova Concelhia</title>
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
          <?php echo $customCSS; ?>
        </head>
        <body>
          <!-- Navbar -->
          <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
              <a class="navbar-brand" href="#">Portal JSD</a>
              <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                  <li class="nav-item"><a class="nav-link active">Bem-vindo, Administrador</a></li>
                  <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
              </div>
            </div>
          </nav>
          
          <!-- Formulário -->
          <div class="container mt-5">
            <h2>Inserir Nova Concelhia</h2>
            <form action="?acao=inserir" method="post">
              <div class="mb-3">
                <label for="Nome_Concelhia" class="form-label">Nome da Concelhia:</label>
                <input type="text" class="form-control" id="Nome_Concelhia" name="Nome_Concelhia" required>
              </div>
              <div class="mb-3">
                <label for="Distrito" class="form-label">Distrito:</label>
                <input type="text" class="form-control" id="Distrito" name="Distrito" required>
              </div>
              <div class="mb-3">
                <label for="Estado" class="form-label">Estado:</label>
                <select name="Estado" id="Estado" class="form-select">
                  <option value="1">Ativo</option>
                  <option value="0">Inativo</option>
                </select>
              </div>
              <button type="submit" class="btn btn-salvar">Salvar</button>
              <a href="gestao_concelhias.php" class="btn btn-secondary">Voltar</a>
            </form>
          </div>
          
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
        exit;
    }
}

/* ==========================
   2. EDITAR
   ========================== */
if ($acao === 'editar') {
    if (!isset($_GET['id'])) {
        echo '<div class="container mt-5"><div class="alert alert-danger">ID não especificado.</div></div>';
        exit;
    }
    $id = intval($_GET['id']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome_concelhia = $_POST['Nome_Concelhia'];
        $distrito       = $_POST['Distrito'];
        $estado         = $_POST['Estado'];

        $sql = "UPDATE concelhias 
                SET Nome_Concelhia='$nome_concelhia',
                    Distrito='$distrito',
                    Estado='$estado'
                WHERE Cod_concelhia=$id";
        if ($conn->query($sql) === TRUE) {
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Registro Atualizado - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Registro atualizado com sucesso!</h1>
                  <p>O registro da concelhia foi atualizado na base de dados.</p>
                  <a href="gestao_concelhias.php" class="btn-msg">Voltar à listagem</a>
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
                  <a href="gestao_concelhias.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        }
        exit;
    } else {
        $sql   = "SELECT * FROM concelhias WHERE Cod_concelhia=$id";
        $query = $conn->query($sql);
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            ?>
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Editar Concelhia</title>
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              <?php echo $customCSS; ?>
              <style>
                .navbar-custom { background-color: orange; }
                .navbar-custom .nav-link { color: white; }
              </style>
            </head>
            <body>
              <nav class="navbar navbar-expand-lg navbar-custom">
                <div class="container-fluid">
                  <a class="navbar-brand" href="#">Portal JSD</a>
                  <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                      <li class="nav-item"><a class="nav-link active">Bem-vindo, Administrador</a></li>
                      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                  </div>
                </div>
              </nav>
              
              <div class="container mt-5">
                <h2>Editar Concelhia</h2>
                <form action="?acao=editar&id=<?php echo $id; ?>" method="post">
                  <div class="mb-3">
                    <label for="Nome_Concelhia" class="form-label">Nome da Concelhia:</label>
                    <input type="text" class="form-control" id="Nome_Concelhia" name="Nome_Concelhia" value="<?php echo $row['Nome_Concelhia']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="Distrito" class="form-label">Distrito:</label>
                    <input type="text" class="form-control" id="Distrito" name="Distrito" value="<?php echo $row['Distrito']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="Estado" class="form-label">Estado:</label>
                    <select name="Estado" id="Estado" class="form-select">
                      <option value="1" <?php echo ($row['Estado'] == 1) ? 'selected' : ''; ?>>Ativo</option>
                      <option value="0" <?php echo ($row['Estado'] == 0) ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-salvar">Salvar Alterações</button>
                  <a href="gestao_concelhias.php" class="btn btn-secondary">Voltar</a>
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

/* ==========================
   3. APAGAR
   ========================== */
if ($acao === 'apagar') {
    if (!isset($_GET['id'])) {
        echo '<div class="container mt-5"><div class="alert alert-danger">ID não especificado.</div></div>';
        exit;
    }
    $id = intval($_GET['id']);
    $sql = "DELETE FROM concelhias WHERE Cod_concelhia=$id";
    if ($conn->query($sql) === TRUE) {
        echo '
        <!DOCTYPE html>
        <html lang="pt">
        <head>
          <meta charset="UTF-8">
          <title>Registro Apagado - Portal JSD</title>
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
            }
          </style>
        </head>
        <body>
          <div class="message-container">
            <div class="alert alert-success">Registro apagado com sucesso!</div>
            <a class="btn btn-primary" href="gestao_concelhias.php">Voltar à listagem</a>
          </div>
        </body>
        </html>';
    } else {
        echo '<div class="container mt-5">
                <div class="alert alert-danger">Erro ao apagar: ' . $conn->error . '</div>
                <a class="btn btn-primary" href="gestao_concelhias.php">Voltar à listagem</a>
              </div>';
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Concelhias - Portal JSD</title>
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
  
  <!-- Listagem de Concelhias -->
  <div class="container mt-5">
    <h1>Gestão de Concelhias</h1>
    <p><a class="btn btn-success" href="?acao=inserir">+ Inserir nova concelhia</a></p>
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nome Concelhia</th>
              <th>Distrito</th>
              <th>Estado</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql   = "SELECT * FROM concelhias ORDER BY Cod_concelhia ASC";
            $query = $conn->query($sql);
            if (!$query) {
                echo "<tr><td colspan='5'>Erro na consulta: " . $conn->error . "</td></tr>";
            } else {
                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Cod_concelhia'] . "</td>";
                        echo "<td>" . $row['Nome_Concelhia'] . "</td>";
                        echo "<td>" . $row['Distrito'] . "</td>";
                        echo "<td>" . ($row['Estado'] == 1 ? "Ativo" : "Inativo") . "</td>";
                        echo "<td>
                                <a class='btn btn-editar btn-sm' href='?acao=editar&id=" . $row['Cod_concelhia'] . "'>Editar</a>
                                <a class='btn btn-apagar btn-sm' href='?acao=apagar&id=" . $row['Cod_concelhia'] . "' onclick=\"return confirm('Tem a certeza que deseja apagar?');\">Apagar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Não há registros.</td></tr>";
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

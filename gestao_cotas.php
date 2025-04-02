<?php
session_start();
require 'conexao.php';       // Ficheiro com as credenciais e conexão à BD
require 'valida_session.php'; // Ficheiro para validar sessão (se necessário)

// Conexão ao banco de dados (caso não esteja dentro do conexao.php)
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// CSS customizado para os botões (Editar, Apagar, Salvar e Inserir Cota)
// As cores definidas abaixo são as originais que você utilizou.
$customCSS = '
<style>
:root {
    --btn-editar-bg: rgb(255, 153, 0);        /* Cor base do botão Editar */
    --btn-editar-bg-hover: rgb(255, 123, 0);    /* Cor de fundo ao passar o mouse */
    --btn-editar-color: #ffffff;              /* Cor do texto do botão Editar */
    
    --btn-apagar-bg: #dc3545;                 /* Cor base do botão Apagar */
    --btn-apagar-bg-hover: #c82333;           /* Cor de fundo ao passar o mouse */
    --btn-apagar-color: #ffffff;              /* Cor do texto do botão Apagar */
    
    --btn-salvar-bg: rgb(255, 153, 0);         /* Cor base do botão Salvar */
    --btn-salvar-bg-hover: rgb(255, 123, 0);     /* Cor de fundo ao passar o mouse */
    --btn-salvar-color: #ffffff;              /* Cor do texto do botão Salvar */
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

/* Botão para inserir cota */
.btn-inserir-cota {
    background-color: rgb(255, 153, 0);
    color: #ffffff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn-inserir-cota:hover {
    background-color: rgb(255, 123, 0);
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
</style>
';

// Captura a ação (inserir, editar, apagar)
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// ============================================================================
// 1. INSERIR
// ============================================================================
if ($acao === 'inserir') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebe dados do formulário
        $cod_concelhia = $_POST['Cod_concelhia'];
        $valor         = $_POST['Valor'];
        $ano           = $_POST['Ano'];
        $cod_militante = $_POST['Cod_militante'];
        $estado        = $_POST['Estado'];  // 1 = Pago, 0 = Em dívida

        // Monta o INSERT
        $sql = "INSERT INTO cotas (Cod_concelhia, Valor, Ano, Cod_militante, Estado)
                VALUES ('$cod_concelhia', '$valor', '$ano', '$cod_militante', '$estado')";
        if ($conn->query($sql) === TRUE) {
            // Página de sucesso
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Cota Inserida - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Cota inserida com sucesso!</h1>
                  <p>A sua nova cota foi inserida na base de dados.</p>
                  <a href="gestao_cotas.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        } else {
            // Página de erro na inserção
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
                  <p>Ocorreu um erro ao inserir a cota: ' . $conn->error . '</p>
                  <a href="gestao_cotas.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        }
        exit;
    } else {
        // Exibe o formulário de inserção
        ?>
        <!DOCTYPE html>
        <html lang="pt">
        <head>
          <meta charset="UTF-8">
          <title>Inserir Nova Cota</title>
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- Bootstrap CSS -->
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
            <h2>Inserir Nova Cota</h2>
            <form action="?acao=inserir" method="post">
              <div class="mb-3">
                <label for="Cod_concelhia" class="form-label">Cód. Concelhia:</label>
                <input type="number" class="form-control" id="Cod_concelhia" name="Cod_concelhia" required>
              </div>
              <div class="mb-3">
                <label for="Valor" class="form-label">Valor (em €):</label>
                <input type="number" step="0.01" class="form-control" id="Valor" name="Valor" required>
              </div>
              <div class="mb-3">
                <label for="Ano" class="form-label">Ano (YYYY):</label>
                <input type="number" class="form-control" id="Ano" name="Ano" placeholder="2025" required>
              </div>
              <div class="mb-3">
                <label for="Cod_militante" class="form-label">Cód. Militante:</label>
                <input type="number" class="form-control" id="Cod_militante" name="Cod_militante" required>
              </div>
              <div class="mb-3">
                <label for="Estado" class="form-label">Estado:</label>
                <select name="Estado" id="Estado" class="form-select">
                  <option value="1">Pago</option>
                  <option value="0">Em dívida</option>
                </select>
              </div>
              <button type="submit" class="btn btn-salvar">Salvar</button>
              <a href="gestao_cotas.php" class="btn btn-secondary">Voltar</a>
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
        // Recebe dados do formulário
        $cod_concelhia = $_POST['Cod_concelhia'];
        $valor         = $_POST['Valor'];
        $ano           = $_POST['Ano'];
        $cod_militante = $_POST['Cod_militante'];
        $estado        = $_POST['Estado'];
        $sql = "UPDATE cotas 
                SET Cod_concelhia='$cod_concelhia',
                    Valor='$valor',
                    Ano='$ano',
                    Cod_militante='$cod_militante',
                    Estado='$estado'
                WHERE Cod_cotas=$id";
        if ($conn->query($sql) === TRUE) {
            // Página de sucesso na atualização
            echo '
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Cota Atualizada - Portal JSD</title>
              <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
              ' . $customCSS . '
            </head>
            <body class="msg-body">
              <div class="msg-wrapper">
                <div class="msg-card">
                  <h1>Cota atualizada com sucesso!</h1>
                  <p>A cota foi editada corretamente na base de dados.</p>
                  <a href="gestao_cotas.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        } else {
            // Página de erro na atualização
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
                  <p>Ocorreu um erro ao atualizar a cota: ' . $conn->error . '</p>
                  <a href="gestao_cotas.php" class="btn-msg">Voltar à listagem</a>
                </div>
              </div>
            </body>
            </html>';
        }
        exit;
    } else {
        // Busca o registro para preencher o formulário
        $sql   = "SELECT * FROM cotas WHERE Cod_cotas=$id";
        $query = $conn->query($sql);
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            ?>
            <!DOCTYPE html>
            <html lang="pt">
            <head>
              <meta charset="UTF-8">
              <title>Editar Cota</title>
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
                <h2>Editar Cota</h2>
                <form action="?acao=editar&id=<?php echo $id; ?>" method="post">
                  <div class="mb-3">
                    <label for="Cod_concelhia" class="form-label">Cód. Concelhia:</label>
                    <input type="number" class="form-control" id="Cod_concelhia" name="Cod_concelhia" value="<?php echo $row['Cod_concelhia']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="Valor" class="form-label">Valor (em €):</label>
                    <input type="number" step="0.01" class="form-control" id="Valor" name="Valor" value="<?php echo $row['Valor']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="Ano" class="form-label">Ano (YYYY):</label>
                    <input type="number" class="form-control" id="Ano" name="Ano" value="<?php echo $row['Ano']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="Cod_militante" class="form-label">Cód. Militante:</label>
                    <input type="number" class="form-control" id="Cod_militante" name="Cod_militante" value="<?php echo $row['Cod_militante']; ?>" required>
                  </div>
                  <div class="mb-3">
                    <label for="Estado" class="form-label">Estado:</label>
                    <select name="Estado" id="Estado" class="form-select">
                      <option value="1" <?php echo ($row['Estado'] == 1) ? 'selected' : ''; ?>>Pago</option>
                      <option value="0" <?php echo ($row['Estado'] == 0) ? 'selected' : ''; ?>>Em dívida</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-salvar">Salvar Alterações</button>
                  <a href="gestao_cotas.php" class="btn btn-secondary">Voltar</a>
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
    $sql = "DELETE FROM cotas WHERE Cod_cotas=$id";
    if ($conn->query($sql) === TRUE) {
        // Página de sucesso na exclusão
        echo '
        <!DOCTYPE html>
        <html lang="pt">
        <head>
          <meta charset="UTF-8">
          <title>Cota Apagada - Portal JSD</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
          ' . $customCSS . '
        </head>
        <body class="msg-body">
          <div class="msg-wrapper">
            <div class="msg-card">
              <h1>Cota apagada com sucesso!</h1>
              <p>A cota foi removida da base de dados.</p>
              <a href="gestao_cotas.php" class="btn-msg">Voltar à listagem</a>
            </div>
          </div>
        </body>
        </html>';
    } else {
        echo '<div class="container mt-5">
                <div class="alert alert-danger">Erro ao apagar: ' . $conn->error . '</div>
                <a class="btn btn-primary" href="gestao_cotas.php">Voltar à listagem</a>
              </div>';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Cotas - Portal JSD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php echo $customCSS; ?>
  <style>
    .navbar-custom { background-color: orange; }
    .navbar-custom .nav-link { color: white; }
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
  
  <!-- Menu (3 itens na primeira linha; 2 na segunda centralizados com offset) -->
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
  
  <!-- Listagem de Cotas -->
  <div class="container mt-5">
    <h1>Gestão de Cotas</h1>
    <p><a class="btn btn-success" href="?acao=inserir">+ Inserir nova cota</a></p>
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Código</th>
              <th>Cód. Concelhia</th>
              <th>Valor (€)</th>
              <th>Ano</th>
              <th>Cód. Militante</th>
              <th>Estado</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql   = "SELECT * FROM cotas ORDER BY Cod_cotas ASC";
            $query = $conn->query($sql);
            if (!$query) {
                echo "<tr><td colspan='7'>Erro na consulta: " . $conn->error . "</td></tr>";
            } else {
                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Cod_cotas'] . "</td>";
                        echo "<td>" . $row['Cod_concelhia'] . "</td>";
                        echo "<td>" . $row['Valor'] . "</td>";
                        echo "<td>" . $row['Ano'] . "</td>";
                        echo "<td>" . $row['Cod_militante'] . "</td>";
                        $estadoText = ($row['Estado'] == 1) ? "Pago" : "Em dívida";
                        echo "<td>" . $estadoText . "</td>";
                        echo "<td>
                                <a class='btn btn-editar btn-sm' href='?acao=editar&id=" . $row['Cod_cotas'] . "'>Editar</a>
                                <a class='btn btn-apagar btn-sm' href='?acao=apagar&id=" . $row['Cod_cotas'] . "' onclick=\"return confirm('Tem a certeza que deseja apagar esta cota?');\">Apagar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Não há registros de cotas.</td></tr>";
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

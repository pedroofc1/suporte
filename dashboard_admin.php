<?php
session_start();
require 'conexao.php';
require 'valida_session.php';
// Esta página deve ser acessível somente se o usuário estiver logado
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal JSD - Administração</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Estilos customizados -->
  <style>
    .navbar-custom { background-color: orange; }
    .navbar-custom .nav-link { color: white; }
  
    /* ==================================================
       Overrides de Botões Bootstrap
       ================================================== */

    /* Botão "Editar" (classe .btn-primary) */
    .btn-primary {
      background-color: #fd7e14 !important; /* Exemplo: laranja */
      border-color: #fd7e14 !important;
      color: #fff !important;              /* Texto em branco */
    }

    /* Hover do botão "Editar" */
    .btn-primary:hover {
      background-color: #e06c13 !important;
      border-color: #fd7e14 !important;
      color: #fff !important;
    }

    /* Botão "Detalhes" (classe .btn-info) */
    .btn-info {
      background-color: #e06c13 !important;
      border-color: #e06c13 !important;
      color: white !important;
    }

    /* Hover do botão "Detalhes" */
    .btn-info:hover {
      background-color: #e06c13 !important;
      border-color: #e06c13 !important;
      color: white !important;
    }

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

    th.acao-col {
      min-width: 160px; 
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
          <li class="nav-item">
            <a class="nav-link active">Bem-vindo, Administrador</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Menu com estrutura igual à dos escritórios -->
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
      <!-- Segunda linha: 2 colunas com offset para centralizar -->
      <div class="col-md-4 offset-md-2 mt-3">
        <div class="icon-box">
          <img src="cotas.png" width="50" alt="Cotas">
          <a href="gestao_cotas.php">Gestão de Cotas</a>
        </div>
      </div>
      <div class="col-md-4 mt-3">
        <div class="icon-box">
          <img src="logoescri.png" width="45" alt="Escritórios">
          <a href="gestao_escritorios.php">Gestão de Escritórios</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tabela de Utilizadores -->
  <div class="container mt-5">
    <div class="card">
      <div class="card-header">
        <h5>Lista de Utilizadores</h5>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Login</th>
              <th>Pass (MD5)</th>
              <th>Status</th>
              <th>Nível</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Seleciona dados da tabela "utilizadores"
            $stmt = $pdo->query("SELECT * FROM utilizadores");
            while ($row = $stmt->fetch()) {
                // Exemplo de colunas: id, nome, login, pass, status, nivel
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['login']}</td>
                        <td>{$row['pass']}</td>
                        <td>" . ($row['status'] === 'ativo' ? 'Ativo' : 'Inativo') . "</td>
                        <td>{$row['nivel']}</td>
                        <td>
                          <button type='button' class='btn btn-primary btn-modal'
                            data-id='{$row['id']}'
                            data-action='editar'>
                            Editar
                          </button>
                        </td>
                      </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Tabela de Eventos -->
    <div class="card mt-3">
      <div class="card-header">
        <h5>Próximos Eventos</h5>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome do Evento</th>
              <th>Data</th>
              <th>Local</th>
              <th>Concelhia</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM gestao_eventos");
            while ($row = $stmt->fetch()) {
              echo "<tr>
                      <td>{$row['Cod_evento']}</td>
                      <td>{$row['Nome_evento']}</td>
                      <td>{$row['Data_evento']}</td>
                      <td>{$row['Local_evento']}</td>
                      <td>{$row['Cod_concelhia']}</td>
                      <td>
                        <button type='button' class='btn btn-info btn-modal'
                          data-id='{$row['Cod_evento']}'
                          data-action='detalhes'>
                          Detalhes
                        </button>
                      </td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Tabela de Cotas -->
    <div class="card mt-3 mb-5">
      <div class="card-header">
        <h5>Gestão de Cotas</h5>
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Concelhia</th>
              <th>Militante</th>
              <th>Valor</th>
              <th>Ano</th>
              <th>Estado</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM cotas");
            while ($row = $stmt->fetch()) {
              echo "<tr>
                      <td>{$row['Cod_cotas']}</td>
                      <td>{$row['Cod_concelhia']}</td>
                      <td>{$row['Cod_militante']}</td>
                      <td>{$row['Valor']}</td>
                      <td>{$row['Ano']}</td>
                      <td>" . ($row['Estado'] ? 'Pago' : 'Em dívida') . "</td>
                      <td>
                        <button type='button' class='btn btn-warning btn-modal'
                          data-id='{$row['Cod_cotas']}'
                          data-action='atualizar'>
                          Atualizar
                        </button>
                      </td>
                    </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Modal Genérico -->
  <div class="modal fade" id="genericModal" tabindex="-1" aria-labelledby="genericModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="genericModalLabel">Título do Modal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <!-- Conteúdo será carregado via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <!-- Botão exibido para ações de edição/atualização -->
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
  
      // Configura o clique dos botões que acionam o modal
      document.querySelectorAll('.btn-modal').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const action = this.getAttribute('data-action');
  
          // Requisição AJAX para carregar o conteúdo do modal
          fetch(`modal_content.php?id=${id}&action=${action}`)
            .then(response => response.text())
            .then(data => {
              genericModalEl.querySelector('.modal-body').innerHTML = data;
  
              // Define o título do modal e a visibilidade do botão salvar
              let title = '';
              if (action === 'detalhes') {
                title = 'Detalhes';
                document.getElementById('btn-save').style.display = 'none';
              } else if (action === 'editar' || action === 'atualizar') {
                title = (action === 'editar') ? 'Editar Registro' : 'Atualizar Registro';
                document.getElementById('btn-save').style.display = 'inline-block';
              }
              genericModalEl.querySelector('.modal-title').textContent = title;
  
              // Exibe o modal
              genericModal.show();
            })
            .catch(error => console.error('Erro ao carregar conteúdo do modal:', error));
        });
      });
  
      // Ação do botão "Salvar mudanças"
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
              // Atualiza a página ou o conteúdo da tabela conforme necessário
              location.reload();
            } else {
              // Em caso de erro, atualiza o conteúdo do modal (ex: exibe mensagens de erro)
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

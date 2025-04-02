<?php
// modal_content.php
require 'conexao.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'detalhes') {
    // Exemplo: exibir detalhes de um evento
    $stmt = $pdo->prepare("SELECT * FROM gestao_eventos WHERE Cod_evento = ?");
    $stmt->execute([$id]);
    $evento = $stmt->fetch();
  
    if ($evento) {
        echo "<p><strong>ID:</strong> " . $evento['Cod_evento'] . "</p>";
        echo "<p><strong>Nome:</strong> " . $evento['Nome_evento'] . "</p>";
        echo "<p><strong>Data:</strong> " . $evento['Data_evento'] . "</p>";
        echo "<p><strong>Local:</strong> " . $evento['Local_evento'] . "</p>";
        echo "<p><strong>Concelhia:</strong> " . $evento['Cod_concelhia'] . "</p>";
    } else {
        echo "<p>Evento não encontrado.</p>";
    }
} elseif ($action === 'editar') {
    // Exemplo: formulário para editar um militante
    $stmt = $pdo->prepare("SELECT * FROM militantes WHERE Cod_militante = ?");
    $stmt->execute([$id]);
    $militante = $stmt->fetch();
  
    if ($militante) {
        echo "<form action='processa_militante.php' method='post'>
                <input type='hidden' name='Cod_militante' value='{$militante['Cod_militante']}'>
                <div class='mb-3'>
                  <label class='form-label'>Nome</label>
                  <input type='text' name='nome' class='form-control' value='{$militante['nome']}' required>
                </div>
                <div class='mb-3'>
                  <label class='form-label'>Login</label>
                  <input type='text' name='login' class='form-control' value='{$militante['login']}' required>
                </div>
                <div class='mb-3'>
                  <label class='form-label'>Senha</label>
                  <input type='password' name='pass' class='form-control' placeholder='Digite nova senha se desejar alterar'>
                </div>
              </form>";
    } else {
        echo "<p>Militante não encontrado.</p>";
    }
} elseif ($action === 'atualizar') {
    // Exemplo: formulário para atualizar uma cota
    $stmt = $pdo->prepare("SELECT * FROM cotas WHERE Cod_cotas = ?");
    $stmt->execute([$id]);
    $cota = $stmt->fetch();
  
    if ($cota) {
        echo "<form action='processa_cota.php' method='post'>
                <input type='hidden' name='Cod_cotas' value='{$cota['Cod_cotas']}'>
                <div class='mb-3'>
                  <label class='form-label'>Concelhia</label>
                  <input type='text' name='Cod_concelhia' class='form-control' value='{$cota['Cod_concelhia']}' required>
                </div>
                <div class='mb-3'>
                  <label class='form-label'>Militante</label>
                  <input type='text' name='Cod_militante' class='form-control' value='{$cota['Cod_militante']}' required>
                </div>
                <div class='mb-3'>
                  <label class='form-label'>Valor</label>
                  <input type='text' name='Valor' class='form-control' value='{$cota['Valor']}' required>
                </div>
                <div class='mb-3'>
                  <label class='form-label'>Ano</label>
                  <input type='text' name='Ano' class='form-control' value='{$cota['Ano']}' required>
                </div>
                <div class='mb-3'>
                  <label class='form-label'>Estado</label>
                  <select name='Estado' class='form-select' required>
                    <option value='1'" . ($cota['Estado'] ? ' selected' : '') . ">Pago</option>
                    <option value='0'" . (!$cota['Estado'] ? ' selected' : '') . ">Em dívida</option>
                  </select>
                </div>
              </form>";
    } else {
        echo "<p>Cota não encontrada.</p>";
    }
} else {
    echo "<p>Ação inválida.</p>";
}
?>

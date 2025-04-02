<?php
// processa_militante.php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id    = $_POST['Cod_militante'];
    $nome  = $_POST['nome'];
    $login = $_POST['login'];
    $pass  = $_POST['pass'];
    
    if (!empty($pass)) {
        // Se nova senha for informada, atualiza com a senha hasheada
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE militantes SET nome = ?, login = ?, pass = ? WHERE Cod_militante = ?");
        $success = $stmt->execute([$nome, $login, $hashedPass, $id]);
    } else {
        // Se não, atualiza apenas os demais campos
        $stmt = $pdo->prepare("UPDATE militantes SET nome = ?, login = ? WHERE Cod_militante = ?");
        $success = $stmt->execute([$nome, $login, $id]);
    }
    
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'html' => '<p>Erro ao atualizar o militante.</p>']);
    }
    exit;
}
?>

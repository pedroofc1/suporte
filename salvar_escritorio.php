<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

$response = ['success' => false, 'html' => ''];

// Se tiver POST de adicionar
if (!isset($_POST['cod_escritorio'])) {
    // Adicionar
    $descricao = $_POST['descricao_escritorio'] ?? '';

    $stmt = $pdo->prepare(\"INSERT INTO escritorios (descricao_escritorio) VALUES (?)\");
    if ($stmt->execute([$descricao])) {
        $response['success'] = true;
    } else {
        $response['html'] = '<p>Erro ao inserir escritório.</p>';
    }
}
// Se tiver POST com cod_escritorio, é edição
else {
    $cod_escritorio = (int)$_POST['cod_escritorio'];
    $descricao = $_POST['descricao_escritorio'] ?? '';

    $stmt = $pdo->prepare(\"UPDATE escritorios SET descricao_escritorio = ? WHERE cod_escritorio = ?\");
    if ($stmt->execute([$descricao, $cod_escritorio])) {
        $response['success'] = true;
    } else {
        $response['html'] = '<p>Erro ao atualizar escritório.</p>';
    }
}

// Retorna JSON para o JavaScript
header('Content-Type: application/json');
echo json_encode($response);

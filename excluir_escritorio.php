<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare(\"DELETE FROM escritorios WHERE cod_escritorio = ?\");
    if ($stmt->execute([$id])) {
        // Excluído com sucesso
        header('Location: gestao_escritorios.php');
        exit;
    } else {
        // Trate o erro caso não consiga excluir
        echo \"<p>Erro ao excluir escritório.</p>\";
    }
} 

else {
    echo \"<p>ID inválido.</p>\";
}

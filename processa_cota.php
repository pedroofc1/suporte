<?php
// processa_cota.php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id            = $_POST['Cod_cotas'];
    $Cod_concelhia = $_POST['Cod_concelhia'];
    $Cod_militante = $_POST['Cod_militante'];
    $Valor         = $_POST['Valor'];
    $Ano           = $_POST['Ano'];
    $Estado        = $_POST['Estado'];
    
    $stmt = $pdo->prepare("UPDATE cotas SET Cod_concelhia = ?, Cod_militante = ?, Valor = ?, Ano = ?, Estado = ? WHERE Cod_cotas = ?");
    $success = $stmt->execute([$Cod_concelhia, $Cod_militante, $Valor, $Ano, $Estado, $id]);
    
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'html' => '<p>Erro ao atualizar a cota.</p>']);
    }
    exit;
}
?>

<?php
session_start();

$produto_id = $_POST['produto_id'];
$variacao_id = $_POST['variacao_id'];
$nome = $_POST['nome'];
$preco = $_POST['preco'];

$key = $produto_id . '_' . $variacao_id;

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_SESSION['carrinho'][$key])) {
    $_SESSION['carrinho'][$key]['quantidade'] += 1;
} else {
    $_SESSION['carrinho'][$key] = [
        'produto_id' => $produto_id,
        'variacao_id' => $variacao_id,
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => 1
    ];
}

header('Location: ver_carrinho.php');
exit;

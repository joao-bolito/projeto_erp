<?php
session_start();
include 'db_conn.php';

$produto_id = $_POST['produto_id'];
$variacao_id = $_POST['variacao_id'];
$quantidade = $_POST['quantidade'];

$stmt = $conn->prepare("SELECT p.nome, p.preco, v.nome_variacao, e.quantidade 
    FROM produtos p
    JOIN variacoes v ON v.id = ?
    JOIN estoque e ON e.variacao_id = v.id
    WHERE p.id = ?");
$stmt->execute([$variacao_id, $produto_id]);
$produto = $stmt->fetch();

if ($quantidade > $produto['quantidade']) {
    die("Erro: estoque insuficiente.");
}

$subtotal = $quantidade * $produto['preco'];

if ($subtotal >= 52 && $subtotal <= 166.59) {
    $frete = 15.00;
} elseif ($subtotal > 200) {
    $frete = 0.00;
} else {
    $frete = 20.00;
}

$_SESSION['carrinho'][] = [
    'produto' => $produto['nome'],
    'variacao' => $produto['nome_variacao'],
    'quantidade' => $quantidade,
    'subtotal' => $subtotal,
    'frete' => $frete
];

echo "Produto adicionado ao carrinho. <a href='ver_carrinho.php'>Ver Carrinho</a>";

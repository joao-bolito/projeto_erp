<?php
include 'db_conn.php';

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'];
$preco = $_POST['preco'];

if ($id) {
    // Atualiza produto existente
    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
    $stmt->execute([$nome, $preco, $id]);
    $produto_id = $id;
} else {
    // Cadastra novo
    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
    $stmt->execute([$nome, $preco]);
    $produto_id = $conn->lastInsertId();
}

foreach ($_POST['variacoes'] as $v) {
    if (!empty($v['id'])) {
        // Atualizar variação existente
        $stmt = $conn->prepare("UPDATE variacoes SET nome_variacao = ? WHERE id = ?");
        $stmt->execute([$v['nome'], $v['id']]);

        $stmt = $conn->prepare("UPDATE estoque SET quantidade = ? WHERE variacao_id = ?");
        $stmt->execute([$v['estoque'], $v['id']]);
    } else {
        // Nova variação
        $stmt = $conn->prepare("INSERT INTO variacoes (produto_id, nome_variacao) VALUES (?, ?)");
        $stmt->execute([$produto_id, $v['nome']]);
        $variacao_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO estoque (produto_id, variacao_id, quantidade) VALUES (?, ?, ?)");
        $stmt->execute([$produto_id, $variacao_id, $v['estoque']]);
    }
}

echo "Produto salvo com sucesso! <a href='produto_form.php?id=$produto_id'>Voltar</a>";

<?php
include 'db_conn.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Deleta estoque
    $conn->prepare("DELETE FROM estoque WHERE produto_id = ?")->execute([$id]);

    // Deleta variações
    $conn->prepare("DELETE FROM variacoes WHERE produto_id = ?")->execute([$id]);

    // Deleta produto
    $conn->prepare("DELETE FROM produtos WHERE id = ?")->execute([$id]);

    header('Location: estoque.php');
    exit;
} else {
    echo "ID inválido.";
}

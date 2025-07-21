<?php
session_start();
include 'db_conn.php';

// Pegar produtos e variações com estoque
$query = "
SELECT p.id AS produto_id, p.nome, p.preco, v.id AS variacao_id, v.nome_variacao, e.quantidade
FROM produtos p
JOIN variacoes v ON v.produto_id = p.id
JOIN estoque e ON e.variacao_id = v.id
WHERE e.quantidade > 0
ORDER BY p.nome, v.nome_variacao
";

$produtos = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Adicionar ao carrinho
if (isset($_POST['produto_id'], $_POST['variacao_id'], $_POST['quantidade'])) {
    $produto_id = $_POST['produto_id'];
    $variacao_id = $_POST['variacao_id'];
    $quantidade = max(1, (int)$_POST['quantidade']);

    // Buscar produto e variação para validar e pegar preço e nome
    $stmt = $conn->prepare("
        SELECT p.nome AS produto_nome, p.preco, v.nome_variacao, e.quantidade AS estoque
        FROM produtos p
        JOIN variacoes v ON v.produto_id = p.id
        JOIN estoque e ON e.variacao_id = v.id
        WHERE p.id = ? AND v.id = ?
    ");
    $stmt->execute([$produto_id, $variacao_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item && $item['estoque'] >= $quantidade) {
        $key = $produto_id . '-' . $variacao_id;

        // Se já existe no carrinho, soma quantidade (validando estoque)
        if (isset($_SESSION['carrinho'][$key])) {
            $novaQtd = $_SESSION['carrinho'][$key]['quantidade'] + $quantidade;
            if ($novaQtd <= $item['estoque']) {
                $_SESSION['carrinho'][$key]['quantidade'] = $novaQtd;
            } else {
                $erro = "Estoque insuficiente para {$item['produto_nome']} ({$item['nome_variacao']})";
            }
        } else {
            $_SESSION['carrinho'][$key] = [
                'produto_id' => $produto_id,
                'variacao_id' => $variacao_id,
                'nome' => $item['produto_nome'] . " ({$item['nome_variacao']})",
                'preco' => $item['preco'],
                'quantidade' => $quantidade,
            ];
        }
        if (!isset($erro)) {
            header('Location: ver_carrinho.php');
            exit;
        }
    } else {
        $erro = "Produto ou variação inválidos ou estoque insuficiente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Estoque</title>
    <style>
        body { 
            font-family: Arial; 
            background: #f4f4f4; 
            padding: 40px; 
        }
        table { 
            width: 80%; margin: auto; background: #fff; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #3498db; color: white; }
        h1 { text-align: center; color: #2c3e50; }
        form { margin: 0; }
        input[type="number"] { width: 60px; padding: 5px; }
        button { background: #27ae60; color: white; border: none; padding: 7px 15px; border-radius: 6px; cursor: pointer; }
        button:hover { background: #1e8449; }
        .erro { text-align: center; color: red; margin-bottom: 20px; }
        a.voltar {
            display: block;
            width: 150px;
            margin: 30px auto 0 auto;
            text-align: center;
            background-color: #2c3e50;
            color: white;
            padding: 12px;
            border-radius: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h1>📋 Estoque Disponível</h1>

<?php if (!empty($erro)): ?>
    <p class="erro"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Variação</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Quantidade</th>
            <th>Comprar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td><?= htmlspecialchars($p['nome_variacao']) ?></td>
            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= $p['quantidade'] ?></td>
            <td>
                <form method="post" style="margin:0;">
                    <input type="hidden" name="produto_id" value="<?= $p['produto_id'] ?>">
                    <input type="hidden" name="variacao_id" value="<?= $p['variacao_id'] ?>">
                    <input type="number" name="quantidade" value="1" min="1" max="<?= $p['quantidade'] ?>" required>
            </td>
            <td>
                    <button type="submit">Comprar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php" class="voltar">⬅ Voltar ao Início</a>

</body>
</html>

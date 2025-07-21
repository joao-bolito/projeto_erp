<?php
session_start();
include 'db_conn.php';

$carrinho = $_SESSION['carrinho'] ?? [];
$subtotal = 0;

foreach ($carrinho as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}

// Calcular frete
if ($subtotal >= 52 && $subtotal <= 166.59) {
    $frete = 15.00;
} elseif ($subtotal > 200) {
    $frete = 0.00;
} else {
    $frete = 20.00;
}

// Remover item do carrinho
if (isset($_GET['remover'])) {
    $key = $_GET['remover'];
    if (isset($_SESSION['carrinho'][$key])) {
        unset($_SESSION['carrinho'][$key]);
        header('Location: ver_carrinho.php');
        exit;
    }
}

// Aplicar cupom
$desconto_cupom = 0;
$cupom_aplicado = null;
$erro_cupom = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cupom'])) {
    $codigoCupom = trim($_POST['cupom']);

    $stmt = $conn->prepare("SELECT * FROM cupons WHERE codigo = ? AND ativo = 1");
    $stmt->execute([$codigoCupom]);
    $cupom = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cupom) {
        $desconto_cupom = ($subtotal * $cupom['desconto_percentual']) / 100;
        $cupom_aplicado = $codigoCupom;
    } else {
        $erro_cupom = "Cupom inválido ou inativo.";
    }
}

$total = $subtotal + $frete - $desconto_cupom;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Carrinho</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        table { width: 80%; margin: auto; background: #fff; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #3498db; color: white; }
        h1 { text-align: center; color: #2c3e50; }
        .total { text-align: right; font-weight: bold; }
        .voltar {
            display: block;
            margin: 30px auto;
            width: 200px;
            text-align: center;
            padding: 12px;
            background-color: #2c3e50;
            color: white;
            border-radius: 10px;
            text-decoration: none;
        }
        .btn-remover {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
        form {
            margin-top: 20px;
            text-align: center;
        }
        input[type="text"] {
            padding: 10px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            background: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
        .erro-msg {
            color: red;
            margin-top: 10px;
        }
        .success-msg {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h1>🛍 Carrinho de Compras</h1>

<?php if ($carrinho): ?>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Qtd</th>
                <th>Subtotal</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($carrinho as $key => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                    <td><a class="btn-remover" href="?remover=<?= urlencode($key) ?>" onclick="return confirm('Remover este item do carrinho?')">❌ Remover</a></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" class="total">Subtotal:</td>
                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="4" class="total">Frete:</td>
                <td>R$ <?= number_format($frete, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="4" class="total">Desconto do Cupom:</td>
                <td>- R$ <?= number_format($desconto_cupom, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="4" class="total">Total:</td>
                <td><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>

    <form method="post">
        <input type="text" name="cupom" placeholder="Código do cupom" required value="<?= htmlspecialchars($cupom_aplicado ?? '') ?>">
        <button type="submit">Aplicar Cupom</button>
    </form>

    <?php if ($erro_cupom): ?>
        <p class="erro-msg"><?= htmlspecialchars($erro_cupom) ?></p>
    <?php elseif ($cupom_aplicado): ?>
        <p class="success-msg">Cupom "<?= htmlspecialchars($cupom_aplicado) ?>" aplicado! Desconto: R$ <?= number_format($desconto_cupom, 2, ',', '.') ?></p>
    <?php endif; ?>

<?php else: ?>
    <p style="text-align:center;">Carrinho vazio 😢</p>
<?php endif; ?>

<a class="voltar" href="estoque.php">⬅ Voltar ao Estoque</a>

</body>
</html>

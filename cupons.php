<?php
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $percentual = $_POST['desconto_percentual'];

    // Evitar duplicatas
    $stmtCheck = $conn->prepare("SELECT id FROM cupons WHERE codigo = ?");
    $stmtCheck->execute([$codigo]);
    if ($stmtCheck->rowCount() === 0) {
        $stmt = $conn->prepare("INSERT INTO cupons (codigo, desconto_percentual, ativo) VALUES (?, ?, 1)");
        $stmt->execute([$codigo, $percentual]);
    } else {
        $erro = "Cupom já existe!";
    }
}

$cupons = $conn->query("SELECT * FROM cupons ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Cupons</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; text-align: center; }
        form, table { margin: auto; background: #fff; padding: 20px; border-radius: 10px; width: 500px; }
        input { width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc; }
        table { margin-top: 30px; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        th { background: #3498db; color: white; }
        h2 { color: #2c3e50; }
        .erro { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>🎁 Gerenciar Cupons</h2>

<?php if (!empty($erro)): ?>
    <p class="erro"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="codigo" placeholder="Código do cupom" required>
    <input type="number" step="0.01" name="desconto_percentual" placeholder="Desconto (%)" min="0" max="100" required>
    <button type="submit">Criar Cupom</button>
</form>

<h3>Cupons Cadastrados</h3>
<table>
    <tr>
        <th>Código</th>
        <th>Desconto (%)</th>
        <th>Ativo</th>
    </tr>
    <?php foreach ($cupons as $cupom): ?>
        <tr>
            <td><?= htmlspecialchars($cupom['codigo']) ?></td>
            <td><?= number_format($cupom['desconto_percentual'], 2, ',', '.') ?>%</td>
            <td><?= $cupom['ativo'] ? 'Sim' : 'Não' ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="index.php" style="display: inline-block; margin-top: 20px; text-decoration: none; color:#3498db;">⬅ Voltar ao Início</a>

</body>
</html>

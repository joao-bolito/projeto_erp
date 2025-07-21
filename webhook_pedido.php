<?php
include 'db_conn.php';

// Recebe o JSON da requisição
$input = file_get_contents('php://input');
$data = json_decode($input, true);

header('Content-Type: application/json');

// Verifica se veio id e status
if (!isset($data['pedido_id'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Campos "pedido_id" e "status" são obrigatórios']);
    exit;
}

$pedido_id = (int)$data['pedido_id'];
$status = strtolower(trim($data['status']));

try {
    if ($status === 'cancelado') {
        // Remove pedido
        $stmt = $conn->prepare("DELETE FROM pedidos WHERE id = ?");
        $stmt->execute([$pedido_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => "Pedido $pedido_id removido com sucesso"]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => "Pedido $pedido_id não encontrado"]);
        }
    } else {
        // Atualiza status do pedido
        $stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $pedido_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => "Pedido $pedido_id atualizado para status '$status'"]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => "Pedido $pedido_id não encontrado"]);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => "Erro interno: " . $e->getMessage()]);
}

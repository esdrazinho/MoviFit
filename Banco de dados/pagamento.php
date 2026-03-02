<?php

require __DIR__ . '/vendor/autoload.php';
require 'conexao.php'; // sua conexão com banco

MercadoPago\SDK::setAccessToken("TOKEN");

// Pegando ID do pedido 
$pedido_id = $_GET['pedido_id'];

// Buscar pedido no banco
$sql = "SELECT * FROM pedidos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) {
    die("Pedido não encontrado.");
}

// Criar pagamento PIX
$payment = new MercadoPago\Payment();
$payment->transaction_amount = (float)$pedido['total'];
$payment->description = "Pedido MoveFit #".$pedido_id;
$payment->payment_method_id = "pix";

$payment->payer = array(
    "email" => "teste@email.com"
);

$payment->save();

// Salvar pagamento no banco
$sql_pagamento = "INSERT INTO pagamentos (pedido_id, metodo, status, valor, codigo_transacao) 
                  VALUES (?, 'PIX', ?, ?, ?)";
$stmt_pag = $conn->prepare($sql_pagamento);
$stmt_pag->bind_param("isds", 
    $pedido_id,
    $payment->status,
    $payment->transaction_amount,
    $payment->id
);
$stmt_pag->execute();

// Mostrar QR Code PIX
echo "<h2>Pagamento PIX</h2>";
echo "<img src='" . $payment->point_of_interaction->transaction_data->qr_code_base64 . "' />";
echo "<br><br>";
echo "<p>Copie o código:</p>";
echo "<textarea>".$payment->point_of_interaction->transaction_data->qr_code."</textarea>";
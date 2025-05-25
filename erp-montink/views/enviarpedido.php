<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'conexao.php';
require 'libs/PHPMailer/PHPMailer.php';
require 'libs/PHPMailer/SMTP.php';
require 'libs/PHPMailer/Exception.php';

session_start();

$nome = $_POST['nome'];
$email = $_POST['email'];

$produtos = [
    1 => ['nome' => 'Produto A', 'preco' => 40.00],
    2 => ['nome' => 'Produto B', 'preco' => 65.00],
];

$mensagem = "Pedido de: $nome ($email)\n\nItens do carrinho:\n";
$total = 0;

foreach ($_SESSION['carrinho'] as $id => $qtd) {
    $produto = $produtos[$id];
    $subtotal = $produto['preco'] * $qtd;
    $total += $subtotal;
    $mensagem .= "- {$produto['nome']} x$qtd - R$ " . number_format($subtotal, 2, ',', '.') . "\n";
}

$mensagem .= "\nTotal: R$ " . number_format($total, 2, ',', '.');

$mail = new PHPMailer(true);

try {
    $sql = "INSERT INTO pedidos (nome, email, itens, total) VALUES (:nome, :email, :itens, :total)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':itens' => $mensagem,
    ':total' => $total,
]);
    // Configurações básicas
    $mail->isSMTP();
    $mail->Host = 'smtp.seudominio.com'; // Ex: smtp.gmail.com
    $mail->SMTPAuth = true;
    $mail->Username = 'seuemail@seudominio.com';
    $mail->Password = 'sua_senha';
    $mail->SMTPSecure = 'tls'; // ou 'ssl'
    $mail->Port = 587; // ou 465 se for SSL

    $mail->setFrom('seuemail@seudominio.com', 'Seu Nome ou Loja');
    $mail->addAddress($email, $nome);

    $mail->Subject = 'Pedido Recebido';
    $mail->Body = $mensagem;

    $mail->send();
    echo "Pedido enviado com sucesso para $email!";
} catch (Exception $e) {
    echo "Erro ao enviar: {$mail->ErrorInfo}";
}

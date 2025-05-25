<?php
session_start();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_GET['adicionar'])) {
    $id = $_GET['adicionar'];
    if (!isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id] = 1;
    } else {
        $_SESSION['carrinho'][$id]++;
    }
}

$produtos = [
    1 => ['nome' => 'Produto A', 'preco' => 40.00],
    2 => ['nome' => 'Produto B', 'preco' => 65.00],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2 class="mb-4">🛒 Carrinho de Compras</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['carrinho'] as $id => $qtd):
                $produto = $produtos[$id];
                $subtotal = $produto['preco'] * $qtd;
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $produto['nome'] ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?= $qtd ?></td>
                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4 class="mb-4">Total: R$ <?= number_format($total, 2, ',', '.') ?></h4>

    <form method="post" action="frete.php" class="row g-3">
        <div class="col-auto">
            <label for="cep" class="form-label">CEP:</label>
            <input type="text" class="form-control" id="cep" name="cep" required placeholder="00000-000">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">Calcular Frete</button>
            <form method="post" action="enviar_pedido.php" class="mt-4">
    <h4>Dados para envio:</h4>
    <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <button type="submit" class="btn btn-success">Enviar Pedido por E-mail</button>
</form>

        </div>
    </form>

    <hr>
    <div class="mt-4">
        <a href="?adicionar=1" class="btn btn-outline-success">Adicionar Produto A</a>
        <a href="?adicionar=2" class="btn btn-outline-success">Adicionar Produto B</a>
        <a href="limpar.php" class="btn btn-danger">Limpar Carrinho</a>
    </div>
</body>
</html>

<?php
require_once 'config/db.php';
require_once 'models/Produto.php';
require_once 'models/Estoque.php';

$produtoModel = new Produto($pdo);
$estoqueModel = new Estoque($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $variacao = $_POST['variacao'];
    $quantidade = $_POST['quantidade'];

    $produto_id = $produtoModel->salvar($nome, $preco);
    $estoqueModel->salvar($produto_id, $variacao, $quantidade);

    header('Location: /mini-erp/index.php');
}

// controllers/CarrinhoController.php
session_start();

class CarrinhoController {
    public function adicionar($produtoId, $quantidade = 1) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produtoId] = $quantidade;
        }
    }

    public function remover($produtoId) {
        unset($_SESSION['carrinho'][$produtoId]);
    }

    public function limpar() {
        unset($_SESSION['carrinho']);
    }

    public function listar() {
        return $_SESSION['carrinho'] ?? [];
    }

    public function totalizar() {
        $total = 0;
        foreach ($this->listar() as $id => $qtd) {
            // simular consulta ao banco (substituir por model real depois)
            $preco = 50; // ex: produto com preço fixo
            $total += $preco * $qtd;
        }
        return $total;
    }
}

<?php
class Estoque {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function salvar($produto_id, $variacao, $quantidade) {
        $stmt = $this->pdo->prepare("INSERT INTO estoques (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
        $stmt->execute([$produto_id, $variacao, $quantidade]);
    }

    public function listarPorProduto($produto_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM estoques WHERE produto_id = ?");
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

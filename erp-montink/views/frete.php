<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
    $dados = json_decode(file_get_contents("https://viacep.com.br/ws/$cep/json/"), true);

    if (isset($dados['uf'])) {
        $uf = $dados['uf'];

        // Exemplo de regras por estado
        switch ($uf) {
            case 'SP':
                $frete = 10.00;
                break;
            case 'RJ':
                $frete = 15.00;
                break;
            default:
                $frete = 20.00;
        }

        // Regra de frete grátis
        session_start();
        $total = 0;
        $produtos = [
            1 => ['nome' => 'Produto A', 'preco' => 40.00],
            2 => ['nome' => 'Produto B', 'preco' => 65.00],
        ];
        foreach ($_SESSION['carrinho'] as $id => $qtd) {
            $total += $produtos[$id]['preco'] * $qtd;
        }

        if ($total >= 100) {
            $frete = 0.00;
        }

        echo "CEP: $cep - Estado: $uf<br>";
        echo "Frete calculado: R$ " . number_format($frete, 2, ',', '.');
        echo "<br><a href='carrinho.php'>Voltar ao carrinho</a>";
    } else {
        echo "CEP inválido. <a href='carrinho.php'>Tentar novamente</a>";
    }
}
?>

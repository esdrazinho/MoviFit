<?php
session_start();
require_once '../Banco de dados/conexao.php'; 

$ids_no_carrinho = array_keys($_SESSION['carrinho'] ?? []);
$produtos_no_carrinho = [];

if (!empty($ids_no_carrinho)) {
    $ids_string = implode(',', $ids_no_carrinho);
    $sql = "SELECT id, nome, preco, imagem, estoque, desconto FROM produtos WHERE id IN ($ids_string)";
    $stmt = $pdo->query($sql);
    $produtos_no_carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$estaLogado = isset($_SESSION['usuario_id']) ? 'true' : 'false';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MoveFit - Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        body { background-color: #121212; color: white; }
        .table { color: white; background: #1e1e1e; border-radius: 10px; overflow: hidden; }
        .table th { background: #333; color: #ccff00; border: none; font-size: 1.1rem; }
        .table td { border-bottom: 1px solid #333; vertical-align: middle; }
        
        input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; }
        .form-control-sm { background: #000; border: 1px solid #444; color: white; width: 70px; text-align: center; font-weight: bold; font-size: 1.1rem; }
        .form-control-sm:focus { background: #222; color: #ccff00; border-color: #ccff00; box-shadow: none; }
        
        .badge-promo { font-size: 0.7rem; background: #ccff00; color: black; padding: 2px 5px; border-radius: 4px; margin-left: 5px; font-weight: bold; }
        .preco-riscado { text-decoration: line-through; color: #ff3333; font-size: 0.9rem; margin-right: 5px; font-weight: 600; }
        .subtotal { color: #ccff00; font-weight: 900; font-size: 1.2rem; text-shadow: 0 0 10px rgba(204, 255, 0, 0.3); }

        .btn-finalizar {
            background-color: #ccff00;
            color: black;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            box-shadow: 0 4px 15px rgba(204, 255, 0, 0.4);
            transition: all 0.3s ease;
        }
        .btn-finalizar:hover { background-color: #b3e600; transform: scale(1.05); }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center fw-bold" style="color: #ccff00;">Seu Carrinho</h2>

    <?php if (empty($produtos_no_carrinho)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x mb-3" style="color: #333;"></i>
            <h3>Seu carrinho está vazio.</h3>
            <a href="home.php" class="btn btn-outline-light mt-3">Voltar as compras</a>
        </div>
    <?php else: ?>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="40%">Produto</th>
                        <th>Preço Unit.</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_geral = 0;
                    foreach ($produtos_no_carrinho as $prod): 
                        $qtd = $_SESSION['carrinho'][$prod['id']];
                        
                        $precoFinal = $prod['preco'];
                        $temDesconto = (isset($prod['desconto']) && $prod['desconto'] > 0);
                        
                        if ($temDesconto) {
                            $precoFinal = $prod['preco'] * (1 - ($prod['desconto'] / 100));
                        }

                        if($qtd > $prod['estoque']) {
                            $qtd = $prod['estoque'];
                            $_SESSION['carrinho'][$prod['id']] = $qtd;
                        }

                        $subtotal = $precoFinal * $qtd;
                        $total_geral += $subtotal;
                    ?>
                    <tr id="linha-<?php echo $prod['id']; ?>">
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $prod['imagem']; ?>" width="70" style="border-radius: 8px; margin-right: 15px; border: 1px solid #333;">
                                <div>
                                    <div class="fw-bold"><?php echo $prod['nome']; ?></div>
                                    <?php if ($temDesconto): ?>
                                        <span class="badge-promo">-<?php echo $prod['desconto']; ?>% OFF</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        
                        <td class="preco-unitario" data-preco="<?php echo $precoFinal; ?>">
                            <?php if ($temDesconto): ?>
                                <div class="preco-riscado">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></div>
                            <?php endif; ?>
                            <span class="fw-bold">R$ <?php echo number_format($precoFinal, 2, ',', '.'); ?></span>
                        </td>
                        
                        <td>
                            <input type="number" 
                                   class="form-control form-control-sm input-qtd" 
                                   data-id="<?php echo $prod['id']; ?>" 
                                   value="<?php echo $qtd; ?>" 
                                   min="1" 
                                   max="<?php echo $prod['estoque']; ?>"
                                   oninput="atualizarValores(this)">
                            <div style="font-size: 0.7rem; color: #666; margin-top: 3px;">Max: <?php echo $prod['estoque']; ?></div>
                        </td>
                        
                        <td class="subtotal">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                        
                        <td>
                            <a href="../PHP/carrinho.php?acao=del&id=<?php echo $prod['id']; ?>" class="btn btn-outline-danger btn-sm border-0"><i class="fas fa-trash fa-lg"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-5 align-items-center">
            <div class="col-md-6 mb-3">
                <a href="home.php" class="btn btn-outline-light px-4 py-2"><i class="fas fa-arrow-left me-2"></i> Continuar Comprando</a>
            </div>
            
            <div class="col-md-6 text-end">
                <div class="mb-4">
                    <span class="text-muted fs-5 me-3">Total a pagar:</span>
                    <span id="total-geral" style="color: #ccff00; font-size: 2.5rem; font-weight: 900; text-shadow: 0 0 15px rgba(204, 255, 0, 0.4);">
                        R$ <?php echo number_format($total_geral, 2, ',', '.'); ?>
                    </span>
                </div>
                
                <button class="btn btn-finalizar btn-lg w-100 py-3 fs-4" onclick="verificarLogin()">
                    Finalizar Compra <i class="fas fa-check-circle ms-2"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function verificarLogin() {
        var logado = <?php echo $estaLogado; ?>;

        if (logado) {
            alert('Sucesso! Redirecionando para o pagamento...');
        } else {
            var irParaLogin = confirm("Você precisa estar logado para finalizar a compra.\nDeseja entrar na sua conta agora?");
            
            if (irParaLogin) {
                window.location.href = '../HTML/login.html';
            }
        }
    }

    function atualizarValores(input) {
        let id = input.getAttribute('data-id');
        let qtd = parseInt(input.value);
        let max = parseInt(input.getAttribute('max'));
        
        if (qtd > max) { alert("Estoque máximo: " + max); input.value = max; qtd = max; }
        if (qtd < 1 || isNaN(qtd)) { qtd = 1; }

        let linha = document.getElementById('linha-' + id);
        let preco = parseFloat(linha.querySelector('.preco-unitario').getAttribute('data-preco'));
        let novoSubtotal = preco * qtd;
        
        linha.querySelector('.subtotal').innerText = novoSubtotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

        let totalGeral = 0;
        document.querySelectorAll('.input-qtd').forEach(function(el) {
            let q = parseInt(el.value);
            let p = parseFloat(el.closest('tr').querySelector('.preco-unitario').getAttribute('data-preco'));
            totalGeral += (p * q);
        });
        document.getElementById('total-geral').innerText = totalGeral.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

        let formData = new FormData();
        formData.append('id', id);
        formData.append('qtd', qtd);
        fetch('../PHP/api_carrinho.php', { method: 'POST', body: formData });
    }
</script>
</body>
</html>
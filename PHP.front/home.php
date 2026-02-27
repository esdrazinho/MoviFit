<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../Banco de dados/conexao.php'; //

$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
$sql = "SELECT * FROM produtos";

if ($filtro == 'masculino') {
    $sql .= " WHERE categoria = 'Masculino'";
} elseif ($filtro == 'feminino') {
    $sql .= " WHERE categoria = 'Feminino'";
} elseif ($filtro == 'lancamentos') {
    $sql .= " WHERE data_adicao >= DATE_SUB(NOW(), INTERVAL 14 DAY)";
}

$sql .= " ORDER BY id DESC";

try {
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produtos = [];
}

$qtd_carrinho = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;

$usuarioLogado = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoveFit - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css"> <style>
        body { background-color: #121212; color: white; }
        .navbar { background: rgba(18, 18, 18, 0.95); border-bottom: 1px solid #333; }
        .card { background-color: #1e1e1e; border: none; transition: transform 0.3s; }
        .card:hover { transform: translateY(-10px); }
        .btn-neon { background-color: #ccff00; color: black; font-weight: bold; border-radius: 50px; border: none; }
        .btn-neon:hover { background-color: #b3e600; }
        .dropdown-menu-dark { background-color: #1e1e1e; border: 1px solid #333; }
        .nav-link.active-filter { color: #ccff00 !important; font-weight: bold; border-bottom: 2px solid #ccff00; }
        
        .preco-antigo { text-decoration: line-through; color: #ff3333; font-size: 0.95rem; margin-right: 8px; font-weight: 600; }
        .badge-desconto { position: absolute; top: 10px; right: 10px; background: #ff3333; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.5); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">MOVE<span style="color: #ccff00;">FIT</span>.</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="home.php?filtro=lancamentos">Lançamentos</a></li>
                    <li class="nav-item"><a class="nav-link" href="home.php?filtro=masculino">Masculino</a></li>
                    <li class="nav-item"><a class="nav-link" href="home.php?filtro=feminino">Feminino</a></li>
                    <li class="nav-item"><a class="nav-link" href="home.php?filtro=todos">Ver Tudo</a></li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    
                    <?php if ($usuarioLogado): ?>
                        <div class="dropdown">
                            <a class="btn btn-outline-light dropdown-toggle border-0" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> Olá, <?php echo htmlspecialchars($usuarioLogado); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="#">Minha Conta</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="../PHP/logout.php">Sair</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="../HTML/login.html" class="text-white text-decoration-none fw-bold" style="font-size: 0.9rem;">
                            <i class="fas fa-user"></i> Entrar
                        </a>
                    <?php endif; ?>
                    
                    <a href="ver_carrinho.php" class="position-relative text-white ms-2">
                        <i class="fas fa-shopping-bag fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $qtd_carrinho; ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero d-flex align-items-center" style="height: 60vh; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; margin-top: 50px;">
        <div class="container">
            <h1 class="display-3 fw-bold text-uppercase">Não pare.<br>Apenas <span style="color:#ccff00">Voe</span>.</h1>
            <p class="lead text-light mb-4">Aproveite nossas ofertas exclusivas.</p>
            <a href="#produtos" class="btn btn-neon btn-lg px-5">Ver Ofertas</a>
        </div>
    </section>

    <div class="container my-5" id="produtos">
        <h2 class="mb-4 border-start border-5 border-warning ps-3" style="border-color: #ccff00 !important;">
            <?php 
                if($filtro == 'lancamentos') echo "Novidades";
                elseif($filtro == 'masculino') echo "Masculino";
                elseif($filtro == 'feminino') echo "Feminino";
                else echo "Destaques & Ofertas";
            ?>
        </h2>

        <div class="row g-4">
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $prod): 
                    $temDesconto = (isset($prod['desconto']) && $prod['desconto'] > 0);
                    $precoFinal = $prod['preco'];
                    
                    if ($temDesconto) {
                        $fator = 1 - ($prod['desconto'] / 100);
                        $precoFinal = $prod['preco'] * $fator;
                    }
                ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 text-white">
                            <?php if ($temDesconto): ?>
                                <span class="badge-desconto">-<?php echo $prod['desconto']; ?>%</span>
                            <?php endif; ?>
                            
                            <img src="<?php echo $prod['imagem']; ?>" class="card-img-top" alt="<?php echo $prod['nome']; ?>" style="height: 250px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted text-uppercase"><?php echo $prod['categoria']; ?></small>
                                <h5 class="card-title mt-2"><?php echo $prod['nome']; ?></h5>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div class="preco-container">
                                        <?php if ($temDesconto): ?>
                                            <span class="preco-antigo">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></span><br>
                                        <?php endif; ?>
                                        <span class="h5 mb-0" style="color: #ccff00;">R$ <?php echo number_format($precoFinal, 2, ',', '.'); ?></span>
                                    </div>
                                    
                                    <?php if ($prod['estoque'] > 0): ?>
                                        <a href="../PHP/carrinho.php?acao=add&id=<?php echo $prod['id']; ?>" class="btn btn-outline-light rounded-circle btn-sm p-2" title="Adicionar ao Carrinho">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled style="font-size: 0.7rem; font-weight: bold; cursor: not-allowed; border-radius: 20px;">
                                            ESGOTADO
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning w-100">Nenhum produto encontrado.</div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
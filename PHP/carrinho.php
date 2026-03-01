<?php
session_start();

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_GET['acao']) && $_GET['acao'] == 'add' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }
    
    if(isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: ../PHP.front/ver_carrinho.php");
    }
    exit;
}

if (isset($_GET['acao']) && $_GET['acao'] == 'del' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
    header("Location: ../PHP.front/ver_carrinho.php");
    exit;
}

if (isset($_POST['acao']) && $_POST['acao'] == 'atualizar') {
    foreach ($_POST['qtd'] as $id => $qtd) {
        $id = intval($id);
        $qtd = intval($qtd);
        
        if ($qtd > 0) {
            $_SESSION['carrinho'][$id] = $qtd;
        } else {
            unset($_SESSION['carrinho'][$id]);
        }
    }
    header("Location: ../PHP.front/ver_carrinho.php");
    exit;
}

// boa sorte com o projeto! Se precisar de mais ajuda, é só chamar!
?>

<?php
session_start();

if (isset($_POST['id']) && isset($_POST['qtd'])) {
    $id = intval($_POST['id']);
    $qtd = intval($_POST['qtd']);

    if ($qtd > 0) {
        $_SESSION['carrinho'][$id] = $qtd;
        echo "ok";
    } else {
        
        unset($_SESSION['carrinho'][$id]);
        echo "removido";
    }
}
?>
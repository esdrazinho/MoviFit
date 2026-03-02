<?php 
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../Banco de dados/conexao.php';

$usuario_id = $_SESSION['usuario_id'];

// Buscar informações do usuário fazer amanha 
 ?>



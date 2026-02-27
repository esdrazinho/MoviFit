<?php
session_start(); 
require '../Banco de dados/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if($email && $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            
            header("Location: ../PHP.front/home.php");
            exit;
        } else {
            echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='../HTML/login.html';</script>";
        }
    }
}
?>
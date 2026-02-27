<?php

require '../Banco de dados/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Processando Cadastro</title>
    <link rel="stylesheet" href="../CSS/style.css"> 
    <style>
        body { padding: 50px; text-align: center; }
        .message-box { background: var(--bg-card); padding: 30px; border-radius: 15px; max-width: 600px; margin: 0 auto; }
        h2 { color: var(--brand-color); }
        ul { list-style: none; padding: 0; text-align: left; }
        li { padding: 10px; border-bottom: 1px solid #333; }
    </style>
</head>
<body>

<div class="message-box">

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if($nome && $email) {
            try {
                $sqlInsert = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
                $stmt = $pdo->prepare($sqlInsert);
                $stmt->execute(['nome' => $nome, 'email' => $email]);
                
                echo "<h2>Sucesso!</h2>";
                echo "<p>O usuário <strong>$nome</strong> foi cadastrado.</p>";
            } catch (PDOException $e) {
            
                if ($e->getCode() == 23000) {
                    echo "<h2 style='color: tomato'>Ops!</h2>";
                    echo "<p>O e-mail <strong>$email</strong> já está cadastrado.</p>";
                } else {
                    echo "<p>Erro técnico: " . $e->getMessage() . "</p>";
                }
            }
        } else {
            echo "<p>Por favor, preencha todos os campos.</p>";
        }
        echo "<hr>";
    }
    ?>

    <h3>Usuários Cadastrados no MoveFit:</h3>
    
    <?php
 
    $sqlSelect = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
    $stmt = $pdo->query($sqlSelect);

    echo "<ul>";
    while ($row = $stmt->fetch()) {
        echo "<li>#" . $row['id'] . " - <strong>" . $row['nome'] . "</strong> <br><small>" . $row['email'] . "</small></li>";
    }
    echo "</ul>";
    ?>

    <br>
    <a href="../HTML/INICIO.HTML" class="btn-cta">Voltar para a Loja</a>

</div>

</body>
</html>
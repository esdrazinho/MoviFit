<?php
require '../Banco de dados/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome    = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_SPECIAL_CHARS);
    $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
   
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS); 
    $senha   = $_POST['senha'];
    
    $cep     = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);
    $rua     = filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_SPECIAL_CHARS);
    $numero  = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_SPECIAL_CHARS);
    $bairro  = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS);
    $cidade  = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS);
    $estado  = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);

    if($nome && $email && $senha && $usuario && $cep && $telefone) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
          
            $sql = "INSERT INTO usuarios (nome, usuario, email, telefone, senha, cep, rua, numero, bairro, cidade, estado) 
                    VALUES (:nome, :usuario, :email, :telefone, :senha, :cep, :rua, :numero, :bairro, :cidade, :estado)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nome'    => $nome,
                'usuario' => $usuario,
                'email'   => $email,
                'telefone'=> $telefone, 
                'senha'   => $senhaHash,
                'cep'     => $cep,
                'rua'     => $rua,
                'numero'  => $numero,
                'bairro'  => $bairro,
                'cidade'  => $cidade,
                'estado'  => $estado
            ]);

            echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='../HTML/login.html';</script>";
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<script>alert('Erro: E-mail ou Usuário já cadastrado!'); window.history.back();</script>";
            } else {
                echo "Erro técnico: " . $e->getMessage();
            }
        }
    } else {
        echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
    }
}
?>
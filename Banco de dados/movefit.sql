-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/03/2026 às 22:29
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `movefit`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `status` enum('Pendente','Pago','Enviado','Entregue') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `estoque` int(11) DEFAULT 10,
  `categoria` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `estoque`, `categoria`) VALUES
(1, 'MoveFit Velocity X', 'Tênis de alta performance para corrida', 299.90, 'https://img.freepik.com/fotos-gratis/sapatos-esportivos-de-moda-azul-para-correr_1203-8095.jpg', 10, 'Running'),
(2, 'Camiseta Tech Dry', 'Tecido respirável para treinos intensos', 89.90, 'https://img.freepik.com/fotos-gratis/jovem-fitness-desportivo-vestuario_23-2149202157.jpg', 10, 'Vestuario'),
(3, 'Kit Halteres 5kg', 'Equipamento ideal para treino em casa', 149.90, 'https://img.freepik.com/fotos-gratis/garrafa-de-agua-e-halteres_23-2148530465.jpg', 10, 'Equipamento'),
(4, 'MoveFit Impact Pro', 'Estabilidade e conforto para crossfit', 349.90, 'https://img.freepik.com/fotos-gratis/tenis-preto-isolado-no-branco_1357-142.jpg', 10, 'Training');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `rua` varchar(150) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `telefone` varchar(20) DEFAULT NULL
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `email`, `senha`, `cep`, `rua`, `numero`, `bairro`, `cidade`, `estado`, `data_cadastro`, `telefone`) VALUES
(1, 'Emilly dos Santos ', 'Einhard', 'Emilly@gmail.com', '$2y$10$BJx3o0r0Vst9BbinL/ILBO9889th9j6qBt2ghXFx5N3aix.4FeFhW', '15722130', 'Rua José Fernandes Montoro', '4306', 'Primavera II', 'Palmeira D&#39;Oeste', 'SP', '2025-12-13 18:55:57', NULL),
(2, 'math deus ', 'joelma gostosa', 'mathinho@hotmail.com', '$2y$10$ODooUHA7spFLJCnvLPoKKeg1EMCV92zlbzbQ1pFGxgJxkLuQiwGp6', '02124041', 'Rua Osaka', '635', 'Jardim Japão', 'São Paulo', 'SP', '2026-02-27 19:18:44', '11965166341');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

CREATE TABLE 'pagamentos' (
  'id' int(11) NOT NULL AUTO_INCREMENT,
  'pedido_id' int(11) NOT NULL,
  'metodo' enum('PIX', 'Cartao', 'boleto') NOT NULL,
  'status' enum('Pendente', 'Aprovado', 'Recusado,' 'Cancelado') DEFAULT 'Pendente',
  'valor' decimal(10,2) NOT NULL,
  'codigo_transacao' varchar(255) DEFAULt NUll,
  'data_pagaemento datetime' DEFAULT current_timestamp(),
  PRIMARY KEY ('id'),
  KEY 'pedido_id' ('pedido_id'),
  CONSTRAINT 'pagamentos_ibfk_1'
  FOREIGN KEY ('pedido_id') REFERENCES 'pedidos' 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* Quando o pagamento for aprovado*/

UPDATE pagamentos
SET status = 'Aprovado'
WHERE id = 1;

ALTER TABLE pedidos
ADD COLUMN pagamento_id INT NULL;

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


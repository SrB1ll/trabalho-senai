<?php
// Verificar se os parâmetros foram passados corretamente pela URL
if (isset($_GET['nome']) && isset($_GET['matricula']) && isset($_GET['curso']) && isset($_GET['data']) && isset($_GET['inicio']) && isset($_GET['computador'])) {
    $nome = $_GET['nome'];
    $matricula = $_GET['matricula'];
    $curso = $_GET['curso'];
    $data = $_GET['data'];
    $inicio = $_GET['inicio'];
    $computador_num = $_GET['computador'];
} else {
    echo "Parâmetros faltando na URL.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .container p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirmação de Cadastro</h2>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
        <p><strong>Matrícula:</strong> <?php echo htmlspecialchars($matricula); ?></p>
        <p><strong>Curso:</strong> <?php echo htmlspecialchars($curso); ?></p>
        <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($data)); ?></p>
        <p><strong>Horário:</strong> <?php echo htmlspecialchars($inicio); ?></p>
        <p><strong>Computador Selecionado:</strong> <?php echo htmlspecialchars($computador_num); ?></p>
    </div>
</body>
</html>

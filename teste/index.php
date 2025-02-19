<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Computador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .ocupado {
            color: red;
        }
        .livre {
            color: green;
        }
    </style>
    <script>
        function buscarComputadoresLivres() {
            var data = document.getElementById('data_pesquisa').value;
            if (data) {
                var nome = document.getElementById('nome').value;
                var matricula = document.getElementById('matricula').value;
                var curso = document.getElementById('curso').value;
                window.location.href = 'index.php?data=' + encodeURIComponent(data) + '&nome=' + encodeURIComponent(nome) + '&matricula=' + encodeURIComponent(matricula) + '&curso=' + encodeURIComponent(curso);
            }
        }
    </script>
</head>
<body>
    <form action="cadastro.php" method="POST">
        <h2>Cadastro de Computador</h2>
        <input type="text" name="nome" id="nome" placeholder="Nome" required value="<?php echo isset($_GET['nome']) ? htmlspecialchars($_GET['nome']) : ''; ?>">
        <input type="text" name="matricula" id="matricula" placeholder="Matrícula" required value="<?php echo isset($_GET['matricula']) ? htmlspecialchars($_GET['matricula']) : ''; ?>">
        <input type="text" name="curso" id="curso" placeholder="Curso" required value="<?php echo isset($_GET['curso']) ? htmlspecialchars($_GET['curso']) : ''; ?>">

        <h2>Pesquisar Computadores Livres</h2>
        <label for="data_pesquisa">Escolha uma data:</label>
        <input type="date" name="data_pesquisa" id="data_pesquisa" required onchange="buscarComputadoresLivres()" value="<?php echo isset($_GET['data']) ? htmlspecialchars($_GET['data']) : ''; ?>">
        
        <?php if (isset($_GET['data'])) { ?>
        <label for="inicio">Início:</label>
        <select name="inicio" id="inicio" required>
            <option value="">Selecione um horário</option>
            <!-- As opções serão preenchidas dinamicamente pelo PHP -->
            <?php
            $data = $_GET['data'];
            $conn = new mysqli('localhost', 'root', '', 'teste');

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $horarios = [
                '08:00', '09:00', '10:00',
                '14:00', '15:00', '16:00',
                '19:00', '20:00'
            ];

            foreach ($horarios as $horario) {
                $datetime_inicio = $data . ' ' . $horario . ":00";
                $datetime_fim = date('Y-m-d H:i:s', strtotime($datetime_inicio . ' +1 hour'));

                $sql = "SELECT * FROM horarios WHERE DATE(inicio) = '$data' AND ((inicio <= '$datetime_inicio' AND fim > '$datetime_inicio') OR (inicio < '$datetime_fim' AND fim >= '$datetime_fim'))";
                $result = $conn->query($sql);

                if ($result->num_rows == 0) {
                    echo "<option value='$horario'>$horario</option>";
                }
            }

            $conn->close();
            ?>
        </select>
        <?php } ?>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>

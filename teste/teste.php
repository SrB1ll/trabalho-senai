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
</head>
<body>
    <form action="cadastro.php" method="POST">
        <h2>Cadastro de Computador</h2>
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="matricula" placeholder="Matrícula" required>
        <input type="text" name="curso" placeholder="Curso" required>
        <label for="computador_num">Computador:</label>
        <select name="computador_num" id="computador_num" required>
            <option value="">Selecione um Computador</option>
            <?php
            // Conexão com a base de dados
            $conn = new mysqli('localhost', 'root', '', 'teste');

            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "SELECT computador_num, status FROM computadores";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $status = $row['status'] == 'livre' ? 'livre' : 'ocupado';
                    $disabled = $row['status'] == 'livre' ? '' : 'disabled';
                    echo "<option value='" . $row['computador_num'] . "' class='$status' $disabled>" . $row['computador_num'] . " (" . $row['status'] . ")</option>";
                }
            } else {
                echo "<option value=''>Nenhum computador disponível</option>";
            }

            $conn->close();
            ?>
        </select>
        <label for="data">Data:</label>
        <input type="date" name="data" id="data" required>
        <label for="inicio">Início:</label>
        <select name="inicio" id="inicio" required>
            <option value="">Selecione um horário</option>
            <?php
            // Exibe opções de horários específicos
            $horarios = [
                '08:00', '09:00', '10:00', '11:00',
                '14:00', '15:00', '16:00', '17:00',
                '19:00', '20:00', '21:00',
            ];
            foreach ($horarios as $horario) {
                echo "<option value='$horario'>$horario</option>";
            }
            ?>
        </select>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>

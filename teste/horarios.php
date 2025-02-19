<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status dos Computadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
    <h2>Status dos Computadores</h2>
    <table>
        <tr>
            <th>Computador</th>
            <th>Status</th>
            <th>Usuário</th>
            <th>Curso</th>
            <th>Horários Ocupados</th>
        </tr>
        <?php
        $conn = new mysqli('localhost', 'root', '', 'teste');

        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        $sql = "SELECT computador_num, status FROM computadores";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row['computador_num'] . "</td>
                    <td class='" . $row['status'] . "'>" . $row['status'] . "</td>
                    <td>";

                $sql_horarios = "SELECT usuario_nome, curso, inicio, fim FROM horarios WHERE computador_num='" . $row['computador_num'] . "'";
                $result_horarios = $conn->query($sql_horarios);

                if ($result_horarios->num_rows > 0) {
                    $usuarios = "";
                    $cursos = "";
                    $horarios_ocupados = "";
                    while($row_horarios = $result_horarios->fetch_assoc()) {
                        // Verifica se os horários são válidos
                        if (!empty($row_horarios['inicio']) && !empty($row_horarios['fim'])) {
                            $inicio = new DateTime($row_horarios['inicio']);
                            $fim = new DateTime($row_horarios['fim']);
                            $usuarios .= $row_horarios['usuario_nome'] . "<br>";
                            $cursos .= $row_horarios['curso'] . "<br>";
                            $horarios_ocupados .= "De: " . $inicio->format('d/m/Y H:i') . " Até: " . $fim->format('d/m/Y H:i') . "<br>";
                        }
                    }
                    echo $usuarios . "</td><td>" . $cursos . "</td><td>" . $horarios_ocupados . "</td>";
                } else {
                    echo "Nenhum horário ocupado</td><td>Nenhum curso</td><td>Nenhum horário ocupado</td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhum computador disponível</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>
</html>

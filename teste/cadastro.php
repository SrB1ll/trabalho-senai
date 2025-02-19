<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $curso = $_POST['curso'];
    $data = $_POST['data_pesquisa'];
    $inicio = $_POST['inicio'];

    $conn = new mysqli('localhost', 'root', '', 'teste');

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verificar se o usuário já se cadastrou duas vezes no mesmo dia
    $sql_verificar_matricula = "SELECT COUNT(*) as total FROM horarios WHERE matricula='$matricula' AND DATE(inicio) = '$data'";
    $result_verificar_matricula = $conn->query($sql_verificar_matricula);
    $row_verificar_matricula = $result_verificar_matricula->fetch_assoc();

    if ($row_verificar_matricula['total'] >= 2) {
        echo "Você já se cadastrou duas vezes para esta data.";
        exit;
    }

    // Selecionar um computador aleatório que esteja livre no horário escolhido
    $datetime_inicio = $data . ' ' . $inicio . ":00";
    $datetime_fim = date('Y-m-d H:i:s', strtotime($datetime_inicio . ' +1 hour'));

    $sql = "SELECT computador_num FROM computadores WHERE computador_num NOT IN (
                SELECT DISTINCT computador_num FROM horarios 
                WHERE DATE(inicio) = '$data' AND 
                ((inicio <= '$datetime_inicio' AND fim > '$datetime_inicio') OR (inicio < '$datetime_fim' AND fim >= '$datetime_fim'))
            ) ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $computador_num = $row['computador_num'];

        // Inserir dados na tabela de horários
        $sql = "INSERT INTO horarios (computador_num, usuario_nome, matricula, curso, inicio, fim) 
                VALUES ('$computador_num', '$nome', '$matricula', '$curso', '$datetime_inicio', '$datetime_fim')";
        if ($conn->query($sql) === TRUE) {
            // Atualizar o status do computador
            $sql_update = "UPDATE computadores SET status='ocupado' WHERE computador_num='$computador_num'";
            $conn->query($sql_update);

            // Redirecionar para a página de confirmação após o cadastro
            header("Location: confirmacao.php?nome=".urlencode($nome)."&matricula=".urlencode($matricula)."&curso=".urlencode($curso)."&data=".urlencode($data)."&inicio=".urlencode($inicio)."&computador=".urlencode($computador_num));
            exit();
        } else {
            echo "Erro ao cadastrar: " . $conn->error;
        }
    } else {
        echo "Nenhum computador disponível no horário selecionado.";
    }

    $conn->close();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $curso = $_POST['curso'];
    $computador_num = $_POST['computador_num'];
    $data = $_POST['data'];
    $inicio = $_POST['inicio'];

    $conn = new mysqli('localhost', 'root', '', 'teste');

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Combina data e hora de início
    $datetime_inicio = $data . ' ' . $inicio . ":00";
    $datetime_fim = date('Y-m-d H:i:s', strtotime($datetime_inicio . ' +1 hour'));

    // Verificar conflitos de horários
    $sql_verificar = "SELECT * FROM horarios WHERE computador_num='$computador_num' AND ((inicio <= '$datetime_inicio' AND fim > '$datetime_inicio') OR (inicio < '$datetime_fim' AND fim >= '$datetime_fim'))";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        echo "O horário selecionado está em conflito com outro agendamento.";
        exit;
    }

    // Inserir dados na tabela de horários
    $sql = "INSERT INTO horarios (computador_num, usuario_nome, matricula, curso, inicio, fim) VALUES ('$computador_num', '$nome', '$matricula', '$curso', '$datetime_inicio', '$datetime_fim')";
    if ($conn->query($sql) === TRUE) {
        // Atualizar o status do computador
        $sql_update = "UPDATE computadores SET status='ocupado' WHERE computador_num='$computador_num'";
        $conn->query($sql_update);

        // Redirecionar para a página de horários após o cadastro
        header("Location: horarios.php");
        exit();
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }

    $conn->close();
}
?>

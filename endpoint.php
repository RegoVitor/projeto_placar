<?php

try {
    $conn = mysqli_connect("localhost", "root", "regoadmin2517@", "jogos_internos");

    $metodo = $_SERVER['REQUEST_METHOD'];
    $rota = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $modalidade = $_GET['modalidade'];
        
    if ($metodo === 'GET' && $rota === '/projeto.placar/endpoint.php') {
        
        $sql = " SELECT j.inicio, tc.nome_time as time_casa, tf.nome_time as time_fora,
        m.nome_modalidade, m.sexo FROM jogo j

        INNER JOIN time tc on j.Time_casa_id = tc.idTime

        INNER JOIN time tf on j.Time_fora_id = tf.idTime

        INNER JOIN chave c on tc.Chave_idChaveamento = c.idChaveamento

        INNER JOIN modalidade m on c.Modalidade_idModalidade = m.idModalidade

        where m.idModalidade = $modalidade";

        $result = mysqli_query($conn, $sql);
        $dados = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $dados [] = $row ;
        }
        echo json_encode($dados);
    } 
    
} catch (Exception $e) { 
        // O que fazer se der erro
        echo "Erro: " . $e->getMessage(); }



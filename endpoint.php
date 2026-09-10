<?php

try {
    $conn = mysqli_connect("localhost", "root", "regoadmin2517@", "jogos_internos");
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET["op"]) && $_GET["op"] == "jogosAgendados") {
        $sql = "SELECT
            j.inicio as data_hora, 
            tc.nome_time AS time_casa, 
            tf.nome_time AS time_fora,
            CONCAT(m.nome_modalidade, ' ', m.sexo) AS modalidade

            FROM jogo j

            INNER JOIN time tc 
               ON j.Time_casa_id = tc.idTime

            INNER JOIN time tf 
                ON j.Time_fora_id = tf.idTime

            INNER JOIN chave c 
                ON tc.Chave_idChaveamento = c.idChaveamento

            INNER JOIN modalidade m 
                ON c.Modalidade_idModalidade = m.idModalidade ";
    } elseif (isset($_GET["op"]) && $_GET["op"] == "jogosAovivo") {
        $sql = "SELECT
    j.inicio AS data_hora, 
    tc.nome_time AS time_casa, 
    tf.nome_time AS time_fora,
    CONCAT(m.nome_modalidade, ' ', m.sexo) AS modalidade

FROM jogo j

INNER JOIN time tc 
    ON j.Time_casa_id = tc.idTime

INNER JOIN time tf 
    ON j.Time_fora_id = tf.idTime

INNER JOIN chave c 
    ON tc.Chave_idChaveamento = c.idChaveamento

INNER JOIN modalidade m 
    ON c.Modalidade_idModalidade = m.idModalidade

WHERE j.idJogo = 5";
    } else {
        exit(json_encode(array()));
    }


    $result = mysqli_query($conn, $sql);

    $dados = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $dados[] = $row;
    }

    echo json_encode($dados);
}

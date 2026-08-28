<?php

try {
    $conn = mysqli_connect("localhost", "root", "regoadmin2517@", "jogos_internos");
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

$modalidade = $_GET['modalidade'];

$result = mysqli_query($conn, "SELECT 
    j.inicio, 
    tc.nome_time AS time_casa, 
    tf.nome_time AS time_fora,
    m.nome_modalidade, 
    m.sexo 
FROM jogo j

INNER JOIN time tc 
    ON j.Time_casa_id = tc.idTime

INNER JOIN time tf 
    ON j.Time_fora_id = tf.idTime

INNER JOIN chave c 
    ON tc.Chave_idChaveamento = c.idChaveamento

INNER JOIN modalidade m 
    ON c.Modalidade_idModalidade = m.idModalidade

WHERE m.idModalidade = $modalidade");

$dados = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dados[] = $row;
}

echo json_encode($dados);

<?php

try {
    $conn = mysqli_connect("localhost", "root", "regoadmin2517@", "jogos_internos");
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET["op"]) && $_GET["op"] == "jogosAgendados") {

        $sql = "SELECT
            j.idJogo,
            j.inicio AS data_hora, 
            tc.nome_time AS time_casa, 
            tf.nome_time AS time_fora,
            j.Time_casa_id AS id_time_casa,
            j.Time_fora_id AS id_time_fora,
            j.pontos_time_casa,
            j.pontos_time_fora,
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

        WHERE j.inicio IS NULL

        ORDER BY j.inicio";
    } elseif (isset($_GET["op"]) && $_GET["op"] == "jogosAovivo") {

        $sql = "SELECT
            j.idJogo,
            j.inicio AS data_hora, 
            tc.nome_time AS time_casa, 
            tf.nome_time AS time_fora,
            j.Time_casa_id AS id_time_casa,
            j.Time_fora_id AS id_time_fora,
            j.pontos_time_casa,
            j.pontos_time_fora,
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

        WHERE j.inicio <= NOW()
        AND j.fim_jogo IS NULL

        ORDER BY j.inicio";
    } elseif (isset($_GET["op"]) && $_GET["op"] == "jogo") {

        $idJogo = $_GET['idJogo'];

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

        WHERE j.idJogo = $idJogo

        LIMIT 1";
    } elseif (isset($_GET["op"]) && $_GET["op"] == "buscarJogosTime") {

        $nomeTime = $_GET["nomeTime"] ?? '';

        $sql = "SELECT
            j.idJogo,
            j.inicio AS data_hora,
            j.fim_jogo,
            tc.nome_time AS time_casa,
            tf.nome_time AS time_fora,
            j.Time_casa_id AS id_time_casa,
            j.Time_fora_id AS id_time_fora,
            j.pontos_time_casa,
            j.pontos_time_fora,
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

        WHERE tc.nome_time LIKE '%$nomeTime%'
           OR tf.nome_time LIKE '%$nomeTime%'

        ORDER BY j.inicio";
    } elseif (isset($_GET["op"]) && $_GET["op"] == "selecionarJogo") {

        $idJogo = $_GET["idJogo"];

        $sql = "SELECT
            j.idJogo,
            j.inicio,
            j.fim_jogo,
            j.pontos_time_casa,
            j.pontos_time_fora

        FROM jogo j

        WHERE j.idJogo = $idJogo

        LIMIT 1";
    }

    $result = mysqli_query($conn, $sql);

    $dados = [];

    while ($row = mysqli_fetch_assoc($result)) {

        if ($_GET["op"] == "jogosAgendados" || $_GET["op"] == "jogosAovivo" || $_GET["op"] == "buscarJogosTime") {

            $row['img_time_casa'] =
                'imagens/' . $row['id_time_casa'] . '.jpg';

            $row['img_time_fora'] =
                'imagens/' . $row['id_time_fora'] . '.jpg';
        }

        $dados[] = $row;
    }

    echo json_encode($dados);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["op"]) && $_POST["op"] == "atualizarJogo") {

        $idJogo = $_POST["idJogo"];
        $pontosCasa = $_POST["pontos_time_casa"];
        $pontosFora = $_POST["pontos_time_fora"];

        $sql = "UPDATE jogo SET
        pontos_time_casa = $pontosCasa,
        pontos_time_fora = $pontosFora
    WHERE idJogo = $idJogo";

        if (mysqli_query($conn, $sql)) {

            echo json_encode([
                "sucesso" => true
            ]);
        } else {

            echo json_encode([
                "sucesso" => false,
                "erro" => mysqli_error($conn)
            ]);
        }
    }


    if (isset($_POST["op"]) && $_POST["op"] == "iniciarJogo") {

        $idJogo = $_POST["idJogo"];

        $sql = "UPDATE jogo SET
            inicio = NOW()
        WHERE idJogo = $idJogo";

        if (mysqli_query($conn, $sql)) {

            echo json_encode([
                "sucesso" => true
            ]);
        } else {

            echo json_encode([
                "sucesso" => false,
                "erro" => mysqli_error($conn)
            ]);
        }
    }


    if (isset($_POST["op"]) && $_POST["op"] == "finalizarJogo") {

        $idJogo = $_POST["idJogo"];

        $sql = "UPDATE jogo SET
            fim_jogo = NOW()
        WHERE idJogo = $idJogo";

        if (mysqli_query($conn, $sql)) {

            echo json_encode([
                "sucesso" => true
            ]);
        } else {

            echo json_encode([
                "sucesso" => false,
                "erro" => mysqli_error($conn)
            ]);
        }
    }
}

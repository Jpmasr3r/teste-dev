<?php

require_once "../php/connection.php";

try {
    $sql = "SELECT * FROM produto";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as &$produto) {
        $sqlUser = "SELECT nome FROM user WHERE user_id = :user_id";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bindParam(":user_id", $produto["user_id"]);
        $stmtUser->execute();
        $produto["usuarioCadastro"] = $stmtUser->fetchColumn(); // Assume que "nome" é uma coluna no SELECT
        unset($produto["user_id"]);
    }

    $output = [
        "result" => $result,
        "error" => false
    ];
    echo json_encode($output);
} catch (Error $erro) {
    $output = [
        "error" => true,
        "message" => "Erro :" . $erro
    ];
    echo json_encode($output);
    exit();
}

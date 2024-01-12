<?php

require_once "../php/connection.php";

try {
    $desativado = $_POST["desativado"];

    if ($desativado) {
        $sql = "UPDATE user SET desativado = TRUE WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $_POST["user_id"]);
        $stmt->execute();
        $output = [
            "error" => false,
            "message" => "Funcionario desativado com sucesso"
        ];
    } else {
        $sql = "UPDATE user SET desativado = FALSE WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $_POST["user_id"]);
        $stmt->execute();
        $output = [
            "error" => false,
            "message" => "Funcionario reativado com sucesso"
        ];
    }

    echo json_encode($output);
} catch (Error $erro) {
    $output = [
        "error" => true,
        "message" => "Erro :" . $erro
    ];
    echo json_encode($output);
    exit();
}

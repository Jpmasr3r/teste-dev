<?php

require_once "../php/connection.php";
session_start();


try {
    $sql = "SELECT user_id FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $_SESSION["user"]["email"]);
    $stmt->execute();

    $deletado = $_POST["deletado"];

    
    if ($deletado) {
        $novoProduto = [
            "user_id" => $stmt->fetchColumn(),
            "produto_id" => $_POST["produto_id"],
            "data_oculto" => $_POST["data"],
            "hora_oculto" => $_POST["hora"]
        ];
        $sql = "UPDATE produto SET
        oculto = TRUE, user_id = :user_id, data_oculto = :data_oculto, hora_oculto = :hora_oculto
        WHERE produto_id = :produto_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($novoProduto);
        $output = [
            "message" => "Produto deletado com sucesso",
            "error" => false
        ];
    } else {
        $novoProduto = [
            "user_id" => $stmt->fetchColumn(),
            "produto_id" => $_POST["produto_id"]
        ];
        $sql = "UPDATE produto SET oculto = FALSE, user_id = :user_id, data_oculto = NULL , hora_oculto = NULL
        WHERE produto_id = :produto_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($novoProduto);
        $output = [
            "message" => "Produto restaurado com sucesso",
            "error" => false
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

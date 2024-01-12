<?php

require_once "../php/connection.php";
session_start();

try {

    $sql = "SELECT user_id FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $_SESSION["user"]["email"]);
    $stmt->execute();

    $novoProduto = [
        "produto_id" => $_POST["produto_id"],
        "nome" => $_POST["nome"],
        "quantidade" => $_POST["quantidade"],
        "local" => $_POST["local"],
        "data" => $_POST["data"],
        "fornecedor" => $_POST["fornecedor"],
        "user_id" => $stmt->fetchColumn()
    ];

    $sql = "SELECT * FROM produto WHERE nome = :nome";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nome", $novoProduto["nome"]);
    $stmt->execute();
    $resultado = $stmt->fetch();

    if ($resultado) {
        unset($novoProduto["nome"]);
        $sql = "UPDATE produto SET
        quantidade = :quantidade, local_prateleira = :local, data_entrada = :data,
        fornecedor = :fornecedor, user_id = :user_id
        WHERE produto_id = :produto_id;";
    } else {
        $sql = "UPDATE produto SET
        nome = :nome, quantidade = :quantidade, local_prateleira = :local, data_entrada = :data,
        fornecedor = :fornecedor, user_id = :user_id
        WHERE produto_id = :produto_id;";
    }


    $stmt = $conn->prepare($sql);
    $stmt->execute($novoProduto);
    $output = [
        "message" => "Produto modificado com sucesso",
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

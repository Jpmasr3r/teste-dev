<?php

require_once "../php/connection.php";
session_start();

try {
    $sql = "SELECT * FROM produto WHERE nome = :nome";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nome", $_POST["nome"]);
    $stmt->execute();
    $resultado = $stmt->fetch();
    if($resultado) {
        $output = [
            "error" => true,
            "message" => "Produto já cadastrado"
        ];
        echo json_encode($output);
        exit();
    }

    $sql = "SELECT user_id FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $_SESSION["user"]["email"]);
    $stmt->execute();
    $resultado = $stmt->fetch();

    $produto = [
        "nome" => $_POST["nome"],
        "quantidade" => $_POST["quantidade"],
        "localPrateleira" => $_POST["localPrateleira"],
        "dataEntrada" => $_POST["dataEntrada"],
        "fornecedor" => $_POST["fornecedor"],
        "user_id" => $resultado["user_id"]
    ];

    $sql = "INSERT INTO
    produto(nome,quantidade,local_prateleira,data_entrada,fornecedor,user_id,oculto)
    VALUES (:nome,:quantidade,:localPrateleira,:dataEntrada,:fornecedor,:user_id,FALSE)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($produto);

    $output = [
        "error" => false,
        "message" => "Produto cadastrado com sucesso"
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

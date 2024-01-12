<?php

require_once "../php/connection.php";
session_start();

$user = [
    "nome" => $_POST["nome"],
    "email" => $_POST["email"],
    "senha" => password_hash($_POST["senha"], PASSWORD_BCRYPT)
];

try {
    //verifica se ja foi cadastrado
    $sql = "SELECT email FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $user["email"]);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        $output = [
            "message" => "Usuario ja cadastrado",
            "error" => true
        ];
        echo json_encode($output);
        exit();
    }

    //inserir na tabela
    $sql = "INSERT INTO user(nome,email,senha,desativado,admin) VALUES (:nome,:email,:senha,FALSE,FALSE)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($user);
    $output = [
        "message" => "Usuario cadastrado com sucesso",
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

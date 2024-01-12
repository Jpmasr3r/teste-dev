<?php

require_once "../php/connection.php";

$user = [
    "nome" => $_POST["nome"],
    "email" => $_POST["email"],
    "senha" => password_hash($_POST["senha"], PASSWORD_BCRYPT),
    "user_id" => $_POST["user_id"]
];

try {

    $sql = "SELECT email FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $user["email"]);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        $emailCadastrado = true;
        $output = [
            "message" => "Usuario ja cadastrado",
            "error" => true
        ];
    }

    //altera
    $sql;
    if (!$_POST["senha"]) {
        unset($user["senha"]);
        if ($emailCadastrado) {
            unset($user["email"]);
            $sql = "UPDATE user SET nome = :nome WHERE user_id = :user_id";
        } else {
            $sql = "UPDATE user SET nome = :nome , email = :email WHERE user_id = :user_id";
        }
    } else {
        if ($emailCadastrado) {
            unset($user["email"]);
            $sql = "UPDATE user SET nome = :nome , senha = :senha WHERE user_id = :user_id";
        } else {
            $sql = "UPDATE user SET nome = :nome , email = :email , senha = :senha WHERE user_id = :user_id";
        }
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($user);
    $output = [
        "error" => false,
        "message" => "Usuario aualizado com sucesso",
        "senha" => $_POST["senha"]
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

<?php

require_once "connection.php";
session_start();

$user = [
    "email" => $_GET["email"],
    "senha" => $_GET["senha"]
];


try {
    $sql = "SELECT email,senha,desativado FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $user["email"]);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result["desativado"]) {
        $output = [
            "message" => "Usuario desativado",
            "error" => true
        ];
        echo json_encode($output);
        exit();
    }

    if ($result && password_verify($user["senha"], $result["senha"])) {
        $_SESSION["user"]["email"] = $result["email"];
        $output = [
            "message" => "Bem Vindo",
            "error" => false
        ];
        echo json_encode($output);
    } else {
        $output = [
            "message" => "Email ou senha incorretos",
            "error" => true
        ];
        echo json_encode($output);
    }
} catch (Error $erro) {
    $output = [
        "error" => true,
        "message" => "Erro :" . $erro
    ];
    echo json_encode($output);
    exit();
}

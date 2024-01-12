<?php

require_once "../php/connection.php";

try {
    $sql = "SELECT nome,email,user_id,desativado FROM user WHERE admin = FALSE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $output = [
        "result" => $stmt->fetchAll(),
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

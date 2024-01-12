<?php

require_once "../php/connection.php";
session_start();

try {
    if (!$_SESSION["user"]) {
        header("Location : /login/index.html");
        exit();
    }

    $sql = "SELECT admin FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $_SESSION["user"]["email"]);
    $stmt->execute();
    $output = [
        "result" => $stmt->fetch(),
        "error" => false
    ];
} catch (Error $erro) {
    $output = [
        "error" => true,
        "message" => "Erro :" . $erro
    ];
    echo json_encode($output);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens</title>
    <script src="script.js" async></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    if ($output["result"]["admin"]) {
        echo "<button id='funcionariosButton' >Pagina de funcionarios</button>";
    } else {
        echo "<button id='funcionariosButton' hidden='true'>Pagina de funcionarios</button>";
    }
    ?>

    <h1>Produtos deletados</h1>
    <div id="areaProdutos">
    </div>
    <div id="erroTextoAlter"></div>

    <button id="voltar">Voltar</button>

</body>

</html>
<?php

require_once "../php/connection.php";
session_start();

$user = $_SESSION["user"];

try {
    $sql = "SELECT admin FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $user["email"]);
    $stmt->execute();
    $output = [
        "result" => $stmt->fetch(),
        "error" => false
    ];

    if (!$output["result"]["admin"]) {
        header("Location: /itens/index.php");
    }
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
    <title>Funcionarios</title>
    <script src="script.js" async></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div>
        <h1>Area de cadastro</h1>
        <div>
            Nome:
            <input type="text" id="nome">
        </div>
        <div>
            Email:
            <input type="text" id="email">
        </div>
        <div>
            Senha:
            <label>
                <input type="password" id="senha">
                <button id="showSenha">Visualizar</button>
            </label>
        </div>
        <div>
            Verificar senha:
            <label>
                <input type="password" id="Vsenha">
            </label>
        </div>
        <div id="erroTexto"></div>
        <div>
            <button id="cadastrar">cadastrar</button>
        </div>
    </div>
    <h1 class="textoProdutos">Usuarios não Adims</h1>
    <h2 class="textoProdutos">Mantenha a senha vazia para não alterar (Minimo 8 caracteres)</h2>
    <div id="areaUsers">
    </div>
    <div id="erroTextoAlter"></div>
    <button id="voltar" >Voltar</button>
</body>

</html>
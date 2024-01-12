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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

    <div>
        <h1>Cadastro de produto</h1>
        <div>
            Nome:
            <input type="text" id="nomeProduto">
        </div>
        <div>
            Quantidade:
            <input type="number" min="0" id="quantProduto">
        </div>
        <div>
            Local na prateleira:
            <input type="text" id="localPrateleira">
        </div>
        <div>
            Data de entrada:
            <input type="date" id="dataDeEntrada">
        </div>
        <div>
            Fornecedor:
            <input type="text" id="fornecedor">
        </div>
        <div id="erroTexto"></div>
        <div>
            <button id="cadastrar">cadastrar</button>
        </div>
    </div>
    <h1>Produtos</h1>
    <label>
        <input type="number" id="filtroId" placeholder="Filtro ID">
        <input type="text" id="filtroNome"  placeholder="Filtro Nome">
        <input type="text" id="filtroPrateleira"  placeholder="Filtro Prateleira">
        <input type="text" id="filtroFornecedor"  placeholder="Filtro Fornecedor">
    </label>
    <div id="areaProdutos">
        
    </div>
    <div id="erroTextoAlter"></div>

    <button id="imprimir">Imprimir PDF</button>
    <button id="ProdutosDeletados">Produtos deletados</button>
    <button id="voltar">Deslogar</button>

</body>

</html>
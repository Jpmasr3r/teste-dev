const funcionariosButton = document.querySelector("#funcionariosButton");
funcionariosButton.addEventListener("click", () => {
    location.href = "/funcionarios/index.php";
});

const voltarButton = document.querySelector("#voltar");
voltarButton.addEventListener("click", () => {
    location.href = "/itens/index.php";
});

const areaProdutos = document.querySelector("#areaProdutos");

const erroTextoAlter = document.querySelector("#erroTextoAlter");

getProdutos();

async function getProdutos() {
    try {
        areaProdutos.innerHTML = "";
        let data = await fetch("/php/getProdutos.php").then(res => res.json());
        data.result.forEach(e => {
            if (e.oculto) {
                let IdInput = document.createElement("input");
                IdInput.value = e.produto_id;
                IdInput.disabled = true;

                let nomeInput = document.createElement("input");
                nomeInput.value = e.nome;
                if (e.quantidade <= 0) {
                    nomeInput.classList.add("semEstoque");
                }
                nomeInput.disabled = true;

                let quantInput = document.createElement("input");
                quantInput.value = e.quantidade;
                quantInput.disabled = true;

                let localInput = document.createElement("input");
                localInput.value = e.local_prateleira;
                localInput.disabled = true;

                let dataInputEntrada = document.createElement("input");
                dataInputEntrada.value = e.data_entrada;
                dataInputEntrada.type = "date";
                dataInputEntrada.disabled = true;

                let dataInputOculto = document.createElement("input");
                dataInputOculto.value = e.data_oculto;
                dataInputOculto.type = "date";
                dataInputOculto.disabled = true;

                let horaInput = document.createElement("input");
                horaInput.value = e.hora_oculto;
                horaInput.type = "time";
                horaInput.disabled = true;

                let fornecedorInput = document.createElement("input");
                fornecedorInput.value = e.fornecedor;
                fornecedorInput.disabled = true;

                let userInput = document.createElement("input");
                userInput.value = e.usuarioCadastro;
                userInput.disabled = true;

                let restaurarButton = document.createElement("button");
                restaurarButton.innerHTML = "Restaurar";

                restaurarButton.addEventListener("click", async () => {
                    restaurarProduto(e.produto_id);
                });

                let produtoLabel = document.createElement("label");
                produtoLabel.appendChild(IdInput);
                produtoLabel.appendChild(nomeInput);
                produtoLabel.appendChild(quantInput);
                produtoLabel.appendChild(localInput);
                produtoLabel.appendChild(dataInputEntrada);
                produtoLabel.appendChild(dataInputOculto);
                produtoLabel.appendChild(horaInput);
                produtoLabel.appendChild(fornecedorInput);
                produtoLabel.appendChild(userInput);
                produtoLabel.appendChild(restaurarButton);

                areaProdutos.appendChild(produtoLabel);
            }
        });
    } catch (error) {
        console.log(error);
    }
}

async function restaurarProduto(produto_id) {
    try {
        let formData = new FormData();
        formData.append("produto_id", produto_id);
        formData.append("deletado", 0);

        let response = await fetch("/php/deleteProduto.php", {
            method: "POST",
            body: formData
        }).then(res => res.json());
        erroTextoAlter.innerHTML = response.message;
        getProdutos();
    } catch (error) {
        console.log(error);
    }
}
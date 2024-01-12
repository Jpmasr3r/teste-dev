const funcionariosButton = document.querySelector("#funcionariosButton");
funcionariosButton.addEventListener("click", () => {
    location.href = "/funcionarios/index.php";
});

const ProdutosDeletados = document.querySelector("#ProdutosDeletados");
ProdutosDeletados.addEventListener("click", () => {
    location.href = "/itens_deletados/index.php";
});

const voltarButton = document.querySelector("#voltar");
voltarButton.addEventListener("click", () => {
    location.href = "/login/index.html";
});

const areaProdutos = document.querySelector("#areaProdutos");

const nomeProdutoInput = document.querySelector("#nomeProduto");
const quantProdutoInput = document.querySelector("#quantProduto");
const localPrateleiraInput = document.querySelector("#localPrateleira");
const dataDeEntradaInput = document.querySelector("#dataDeEntrada");
const fornecedorInput = document.querySelector("#fornecedor");

const erroTextoAlter = document.querySelector("#erroTextoAlter");

const erroTexto = document.querySelector("#erroTexto");
const cadastrarButton = document.querySelector("#cadastrar");
cadastrarButton.addEventListener("click", setProduto);

const filtroId = document.querySelector("#filtroId");
const filtroNome = document.querySelector("#filtroNome");
const filtroPrateleira = document.querySelector("#filtroPrateleira");
const filtroFornecedor = document.querySelector("#filtroFornecedor");

filtroId.addEventListener("input", () => {
    getProdutos(true);
});

filtroNome.addEventListener("input", () => {
    getProdutos(true);
});

filtroPrateleira.addEventListener("input", () => {
    getProdutos(true);
});

filtroFornecedor.addEventListener("input", () => {
    getProdutos(true);
});

const imprimirButton = document.querySelector("#imprimir");

getProdutos(true);

async function setProduto() {
    try {
        let produto = {
            nome: nomeProdutoInput.value,
            quantidade: quantProdutoInput.value,
            localPrateleira: localPrateleiraInput.value,
            dataEntrada: dataDeEntradaInput.value,
            fornecedor: fornecedorInput.value
        }
        let faltaInf = false;
        let formData = new FormData();
        for (const i in produto) {
            let e = produto[i];
            formData.append(i, e);
            if (e.length <= 0) {
                faltaInf = true;
                break;
            }
        }

        if (faltaInf) {
            erroTexto.innerHTML = "Faltam informações";
        } else {
            let response = await fetch("/php/cadastrarProduto.php", {
                method: "POST",
                body: formData
            }).then(res => res.json());
            erroTexto.innerHTML = response.message;
            getProdutos(true);
        }
    } catch (error) {
        console.log(error);
    }
}

async function getProdutos(buttons) {
    try {
        let filtros = {};

        if (filtroId.value.trim().length > 0) {
            filtros.id = filtroId.value;
        }

        if (filtroNome.value.trim().length > 0) {
            filtros.nome = filtroNome.value.toLowerCase();
        }

        if (filtroPrateleira.value.trim().length > 0) {
            filtros.prateleira = filtroPrateleira.value.toLowerCase();
        }

        if (filtroFornecedor.value.trim().length > 0) {
            filtros.fornecedor = filtroFornecedor.value.toLowerCase();
        }

        areaProdutos.innerHTML = "";
        let data = await fetch("/php/getProdutos.php").then(res => res.json());
        data.result.forEach(e => {
            if (!e.oculto) {
                if ((!filtros.id || String(e.produto_id).includes(String(filtros.id))) &&
                    (!filtros.nome || e.nome.toLowerCase().includes(filtros.nome)) &&
                    (!filtros.prateleira || e.local_prateleira.toLowerCase().includes(filtros.prateleira)) &&
                    (!filtros.fornecedor || e.fornecedor.toLowerCase().includes(filtros.fornecedor))) {

                    let IdInput = document.createElement("input");
                    IdInput.value = e.produto_id;
                    IdInput.disabled = true;

                    let nomeInput = document.createElement("input");
                    nomeInput.value = e.nome;
                    if (e.quantidade <= 0) {
                        nomeInput.classList.add("semEstoque");
                    }

                    let quantInput = document.createElement("input");
                    quantInput.value = e.quantidade;

                    let localInput = document.createElement("input");
                    localInput.value = e.local_prateleira;

                    let dataInput = document.createElement("input");
                    dataInput.value = e.data_entrada;
                    dataInput.type = "date";

                    let fornecedorInput = document.createElement("input");
                    fornecedorInput.value = e.fornecedor;

                    let userInput = document.createElement("input");
                    userInput.value = e.usuarioCadastro;
                    userInput.disabled = true;

                    let produtoLabel = document.createElement("label");
                    produtoLabel.appendChild(IdInput);
                    produtoLabel.appendChild(nomeInput);
                    produtoLabel.appendChild(quantInput);
                    produtoLabel.appendChild(localInput);
                    produtoLabel.appendChild(dataInput);
                    produtoLabel.appendChild(fornecedorInput);
                    produtoLabel.appendChild(userInput);
                    if (buttons) {
                        let buttonModificar = document.createElement("button");
                        buttonModificar.innerHTML = "Modificar";

                        buttonModificar.addEventListener("click", () => {
                            alterProduto(e.produto_id, nomeInput.value, quantInput.value, localInput.value, dataInput.value, fornecedorInput.value);
                        });

                        let buttonDeletar = document.createElement("button");
                        buttonDeletar.innerHTML = "Deletar";

                        buttonDeletar.addEventListener("click", () => {
                            let date = new Date();
                            deleteProduto(e.produto_id,
                                `${date.getFullYear()}-${(date.getMonth() + 1)}-${date.getDate()}`,
                                `${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`)
                        });
                        produtoLabel.appendChild(buttonModificar);
                        produtoLabel.appendChild(buttonDeletar);
                    }

                    areaProdutos.appendChild(produtoLabel);
                };

            }
        });
    } catch (error) {
        console.log(error);
    }
}

async function deleteProduto(produto_id, data, hora) {
    try {
        let formData = new FormData();
        formData.append("produto_id", produto_id);
        formData.append("data", data);
        formData.append("hora", hora);
        formData.append("deletado", 1);

        let response = await fetch("/php/deleteProduto.php", {
            method: "POST",
            body: formData
        }).then(res => res.json());
        erroTextoAlter.innerHTML = response.message;
        getProdutos(true);
    } catch (error) {
        console.log(error);
    }
}

async function alterProduto(produto_id, nome, quant, local, data, fornecedor) {
    try {
        let novoProduto = {
            produto_id: produto_id,
            nome: nome,
            quantidade: quant,
            local: local,
            data: data,
            fornecedor: fornecedor
        };

        if (novoProduto.quantidade < 0) {
            novoProduto.quantidade = 0;
        }

        let faltaInf = false;
        let formData = new FormData();
        for (const i in novoProduto) {
            let e = novoProduto[i];
            formData.append(i, e);
            if (String(e).length <= 0) {
                faltaInf = true;
                break;
            }
        }

        if (faltaInf) {
            erroTextoAlter.innerHTML = "Faltam informações";
        } else {
            let response = await fetch("/php/alterProduto.php", {
                method: "POST",
                body: formData
            }).then(res => res.json());
            erroTextoAlter.innerHTML = response.message;
            getProdutos(true);
        }
    } catch (error) {
        console.log(error);
    }
};

imprimirButton.addEventListener("click", imprimir);

async function imprimir() {
    try {
        let option = {
            margin: [5, 5, 5, 5],
            filename: "produtos.pdf",
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: "mm",
                format: "a3",
                orientation: "portrait"
            }
        };
        await getProdutos(false);
        await html2pdf().set(option).from(areaProdutos).save();
        await getProdutos(true);

    } catch (error) {
        console.log(error);
    }
}
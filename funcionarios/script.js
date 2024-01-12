const nomeInput = document.querySelector("#nome");
const emailInput = document.querySelector("#email");
const senhaInput = document.querySelector("#senha");
const VsenhaInput = document.querySelector("#Vsenha");
const cadastrarButton = document.querySelector("#cadastrar");
const showSenha = document.querySelector("#showSenha");
const erroTexto = document.querySelector("#erroTexto");

const areaUsers = document.querySelector("#areaUsers");
const erroTextoAlter = document.querySelector("#erroTextoAlter");

const voltarButton = document.querySelector("#voltar");
voltarButton.addEventListener("click", () => {
    location.href = "/itens/index.php";
});

getUsers();

showSenha.addEventListener("click", () => {
    if (senhaInput.type == "text") {
        senhaInput.type = "password";
        VsenhaInput.type = "password";
    } else {
        senhaInput.type = "text";
        VsenhaInput.type = "text";
    }
})

cadastrarButton.addEventListener("click", setUser);

async function setUser() {
    try {
        if (senhaInput.value == VsenhaInput.value &&
            (nomeInput.value.length > 0 && emailInput.value.length > 0 && senhaInput.value.length >= 8)) {
            let user = {
                "nome": nomeInput.value,
                "email": emailInput.value,
                "senha": senhaInput.value,
            }

            let formData = new FormData();
            for (const i in user) {
                let e = user[i];
                formData.append(i, e);
            }

            let response = await fetch("/php/cadastrarUsuario.php", {
                method: "POST",
                body: formData
            }).then(res => res.json());
            erroTexto.innerHTML = response.message;

            getUsers();

        } else {
            if (!nomeInput.value.length > 0 && !emailInput.value.length > 0 && !senhaInput.value.length > 0) {
                erroTexto.innerHTML = "Dados incompletos";
            } else if (senhaInput.value.length < 8) {
                erroTexto.innerHTML = "Senha muito curta. Minimo 8 caracteres";
            } else if (senhaInput.value != VsenhaInput.value) {
                erroTexto.innerHTML = "As senha não correspondem";
            } else {
                erroTexto.innerHTML = "";
            }
        }
    } catch (error) {
        console.log(error);
    }
}

async function getUsers() {
    try {
        areaUsers.innerHTML = "";
        let data = await fetch("/php/getUsers.php").then(res => res.json());
        data.result.forEach(e => {
            let inputId = document.createElement("input");
            inputId.value = `ID - ${e.user_id}`;
            inputId.disabled = true;

            let inputNome = document.createElement("input");
            inputNome.value = e.nome;
            inputNome.placeholder = "Nome";

            let inputEmail = document.createElement("input");
            inputEmail.value = e.email;
            inputEmail.placeholder = "Email";

            let inputSenha = document.createElement("input");
            inputSenha.placeholder = "Senha";

            let buttonModificar = document.createElement("button");
            buttonModificar.innerHTML = "Modificar";

            buttonModificar.addEventListener("click", () => {
                alterUser(inputNome.value, inputEmail.value, inputSenha.value, e.user_id);
            })

            let buttonDesativar = document.createElement("button");
            if (e.desativado) {
                inputNome.classList.add("desativado");
                buttonDesativar.innerHTML = "Reativar";
            } else {
                buttonDesativar.innerHTML = "Desativar";
            }

            buttonDesativar.addEventListener("click", () => {
                desativarUser(e.user_id, e.desativado);
            })

            let labelUser = document.createElement("label");
            labelUser.appendChild(inputId);
            labelUser.appendChild(inputNome);
            labelUser.appendChild(inputEmail);
            labelUser.appendChild(inputSenha);
            labelUser.appendChild(buttonModificar);
            labelUser.appendChild(buttonDesativar);

            areaUsers.appendChild(labelUser);
        });
    } catch (error) {
        console.log(error);
    }
}

async function desativarUser(user_id, desativado) {
    try {
        let formData = new FormData();
        formData.append("user_id", user_id);
        if (desativado) {
            formData.append("desativado", 0);
        } else {
            formData.append("desativado", 1);
        }

        let response = await fetch("/php/desativarUser.php", {
            method: "POST",
            body: formData
        }).then(res => res.json());

        erroTextoAlter.innerHTML = response.message;
        getUsers();
    } catch (error) {
        console.log(error);
    }
};

async function alterUser(nome, email, senha, user_id) {
    try {
        let formData = new FormData();
        formData.append("nome", nome);
        formData.append("email", email);
        if (senha.length < 8 && senha.length > 0) {
            erroTextoAlter.innerHTML = "Senha não altera, minimo 8 caracteres";
        } else {
            formData.append("senha", senha);
        }
        formData.append("user_id", user_id);

        let response = await fetch("/php/alterUser.php", {
            method: "POST",
            body: formData
        }).then(res => res.json());

        erroTextoAlter.innerHTML = response.message;
        getUsers();


    } catch (error) {
        console.log(error);
    }
}
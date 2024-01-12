const emailInput = document.querySelector("#email");
const senhaInput = document.querySelector("#senha");
const loginButton = document.querySelector("#login");
const showSenha = document.querySelector("#showSenha");
const erroTexto = document.querySelector("#erroTexto");

loginButton.addEventListener("click",getUser);

async function getUser() {
    try {
        let response = await fetch(`/php/login.php?email=${emailInput.value}&senha=${senhaInput.value}`)
        .then(res => res.json());
        erroTexto.innerHTML = response.message;
        if(!response.error) {
            location.href = "/itens/index.php";
        }
    } catch (error) {
        console.log(error);
    }
}

showSenha.addEventListener("click",() => {
    if(senhaInput.type == "text") {
        senhaInput.type = "password";
    }else {
        senhaInput.type = "text";
    }
})


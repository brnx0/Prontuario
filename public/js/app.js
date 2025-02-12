document.getElementById("toggleMenu").addEventListener("click", function() {
    let sidebar = document.getElementById("sidebar");
    let container = document.querySelector(".containerPrincipal");
    sidebar.classList.toggle("hidden-sidebar");
    if (sidebar.classList.contains("hidden-sidebar")) {
        container.classList.add("full-width");
    } else {
        container.classList.remove("full-width");
    }
});

document.addEventListener("DOMContentLoaded", function (){
    document.querySelectorAll(".edit-btn").forEach(button  =>{
        button.addEventListener("click", function(){
            document.getElementsByName('pes_cod')[0].value = this.getAttribute("data-pes_cod");
            document.getElementsByName('NOME')[1].value = this.getAttribute("data-nome");
            document.getElementsByName('FILICAO_1')[0].value = this.getAttribute("data-filicao_1");
            document.getElementsByName('FILICAO_2')[0].value = this.getAttribute("data-filicao_2");
            document.getElementsByName('CEP')[0].value = this.getAttribute("data-cep");
            document.getElementsByName('LOGRADOURO')[0].value = this.getAttribute("data-logradouro");
            document.getElementsByName('CIDADE')[0].value = this.getAttribute("data-cidade");
            document.getElementsByName('UF')[0].value = this.getAttribute("data-uf");
            document.getElementsByName('TEL_1')[0].value = this.getAttribute("data-tel_1");
            document.getElementsByName('TEL_2')[0].value = this.getAttribute("data-tel_2");
            document.getElementsByName('EMAIL')[0].value = this.getAttribute("data-email");
            document.getElementsByName('CARTAO_SUS')[0].value = this.getAttribute("data-cartao_sus");
            document.getElementsByName('Ativo')[0].value = this.getAttribute("data-ativo");
            document.getElementsByName('PROF_COD')[0].value = this.getAttribute("data-prof_cod");
        })
    })
});
document.addEventListener('DOMContentLoaded', ()=>{
    document.getElementById('btnCadastro').addEventListener('click', ()=>{
        document.getElementById('PacienteForm').reset();
    })
});

function confirmarExclusao(ID, token){
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Você não poderá reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, deletar registro!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/paciente/${ID}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json'
                }
            })
                window.location.reload();
        }
    })
}

function calcularIdade(idade, input){
    if(idade){
        if(new Date(idade) <= new Date()){
            let calcIdade = new Date().getFullYear() - new Date(idade).getFullYear()
            document.getElementById(input).value = calcIdade;
        }else{
            document.getElementById('nascimento').value = null;
            Swal.fire({
                icon: 'error',
                title: '',
                text: 'A data de nascimento não pode ser maior que o dia atual.'
             });
            document.getElementById(input).value = null;
        }
    }else{
        document.getElementById(input).value = null;
    }
}

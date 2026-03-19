function maskCpf(elemento) {
    let value = elemento.value.replace(/\D/g, ''); 
    if (value.length > 3 && value.length <= 6) {
        value = value.replace(/^(\d{3})(\d+)/, '$1.$2');
    } else if (value.length > 6 && value.length <= 9) {
        value = value.replace(/^(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
    } else if (value.length > 9) {
        value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
    }
    elemento.value = value;
}
document.addEventListener('DOMContentLoaded',()=>{
    const tdCpf = document.getElementsByClassName('cpf');
    let tamanho = tdCpf.length;
    for(let i = 0; i< tamanho; i++){
        tdCpf[i].innerText = tdCpf[i].innerText.replace(/^(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
    }
})
function ativarInativarRegistro(med_cod, status) {
    document.getElementById('status').value = status;
    document.getElementById('pac_cod').value = med_cod;
    document.getElementById('editStatusPaciente').submit();  
}


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

/**Refactory */

async function  getPaciente(pac_id){
        return $.ajax({
            url:`/paciente/${pac_id}`,
            method: 'GET',
            dataType: 'json',
            headers: {
                'Content-Type': 'application/json'
            }
        })
}

async function editPaciente(pac_id){
    if(pac_id){
        const paciente = await getPaciente(pac_id);
        let modalPaciente = new bootstrap.Modal($('#pacienteModal'));
        modalPaciente.show()
        $('#nome').val(paciente.nome)
    }
    
}
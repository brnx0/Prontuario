async function excluirEnf(enf_cod){
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
        if(result.isConfirmed){
            document.getElementById('ENF_COD').value = enf_cod;
            if(document.getElementById('ENF_COD').value != ''){
                document.getElementById('deletEnf').submit();
            }
        }

      
    })
}
function ativarInativarENF(enf_cod, status) {
    document.getElementById('Status').value = status;
    document.getElementById('edENF_COD').value = enf_cod;
    document.getElementById('editStatusEnf').submit();
    
}

function preencherDados(dados){
    document.getElementById('enf_cod').value = dados.enf_cod;
    document.getElementById('nomeEnf').value = dados.enf_nome;
    document.getElementById('creEnf').value = dados.cre;
    abrirModal();
    
}
function abrirModal(modo){
    let modal = new bootstrap.Modal(document.getElementById('enfModal'));
    if(modo){
       document.getElementById('formEnf').reset()
    }
    modal.show()
}
async function getEnfermeiro(enf_cod){
    try{
        let result = await (await fetch(`/enfermeiro/${enf_cod}`)).json()
        console.log(result)
        preencherDados(result);
    }catch(erro){
        Swal.fire({
            title:'Aconteceu um erro inesperado. Caso o erro persista, contate o suporte.',
            text: erro,
            icon: 'error'
        })
    }
    
}

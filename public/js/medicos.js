async function excluirMedico(med_cod){
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
            document.getElementById('MED_COD').value = med_cod;
            if(document.getElementById('MED_COD').value != ''){
                document.getElementById('deletMedico').submit();
            }
        }

      
    })
}
function ativarInativarMed(med_cod, status) {
    document.getElementById('Status').value = status;
    document.getElementById('edMED_COD').value = med_cod;
    document.getElementById('editStatusMed').submit();  
}
function preencherDados(dados){
    document.getElementById('med_cod').value = dados.med_cod;
    document.getElementById('nomeMedico').value = dados.med_nome;
    document.getElementById('crmMedico').value = dados.crm;
    abrirModal();
    
}
function abrirModal(modo){
    let modal = new bootstrap.Modal(document.getElementById('medicoModal'));
    if(modo){
       document.getElementById('formMedico').reset()
    }
    modal.show()
}
async function getMedico(med_id){
    try{
        let result = await (await fetch(`/medico/${med_id}`)).json()
        preencherDados(result);
    }catch(erro){
        Swal.fire({
            title:'Aconteceu um erro inesperado. Caso o erro persista, contate o suporte.',
            text:erro,
            icon: 'error'
        })
    }
    
}


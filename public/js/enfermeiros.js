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


function ativarInativarEspc(enf_cod, status) {
    document.getElementById('status').value = status;
    document.getElementById('espc_cod').value = enf_cod;
    document.getElementById('editStatusEspecialidade').submit();   
}

async function excluirEsc(esp_cod){
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
            document.getElementById('edEsp_cod').value = esp_cod;
            if(document.getElementById('edEsp_cod').value != ''){
                document.getElementById('deletEspecialidade').submit();
            }
        }

    })
}
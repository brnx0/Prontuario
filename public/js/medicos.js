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
    console.log(document.getElementById('Status').value,document.getElementById('edMED_COD').value)
    
}


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
function ativarInativarRegistro(med_cod, status) {
    document.getElementById('status').value = status;
    document.getElementById('pac_cod').value = med_cod;
    document.getElementById('editStatusPaciente').submit();  
}

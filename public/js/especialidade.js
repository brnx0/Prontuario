function ativarInativarEspc(enf_cod, status) {
    document.getElementById('status').value = status;
    document.getElementById('espc_cod').value = enf_cod;
    document.getElementById('editStatusEspecialidade').submit();   
}
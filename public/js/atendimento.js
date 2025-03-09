//Editor Receituario
    const quill = new Quill('#editorReceituario', {
        theme: 'snow',
        placeholder: 'Digite os dados da receita'
  });
async function salvarAtendimento(){
    const result = quill.root.innerHTML;
    document.getElementById('receituario').value = result
    console.log(result)
   

}

function toggleDropdown(elemeto) {
    const dropdown = document.getElementById(elemeto);
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function selectOption(element,PAC_COD,input,elementoFiltro, elementDrop) {
    document.getElementById(elementoFiltro).textContent = element.textContent;
    document.getElementById(elementDrop).style.display = 'none';
    document.getElementsByName(input)[0].value = PAC_COD;
}

function filterOptions(elemento, idFiltro) {
    const filter = elemento.value.toLowerCase();
    const optionsContainer = document.getElementById(idFiltro); // Obtém o contêiner das opções
    const options = [...optionsContainer.children].filter(child => child.classList.contains('option')); // Filtra as opções com a classe 'option'

    options.forEach(option => { 
        option.style.display = option.textContent.toLowerCase().includes(filter) ? 'block' : 'none';
    });
}

document.addEventListener('click', function(event) {
    if (!document.querySelector('.select-container').contains(event.target)) {
        document.querySelector('.dropdown').style.display = 'none';
    }
});

async function abrirAtendimento(atend_cod){
    document.getElementById('btnModal').disable = true;
    const req =   await (await (fetch(`/historico/${atend_cod}`))).json()
    let modal = new bootstrap.Modal(document.getElementById('atendimentoModal'));
    await preencherDados(req);
    modal.show();
    document.getElementById('btnModal').disable = false;
  
}
async function preencherDados(dados){
    document.getElementById("atend_cod").value = dados.atend_cod;
    document.getElementById("bpm").value = dados.bpm;
    document.getElementById("dtAtendimento").value = dados.dt_atendimento;
    document.getElementById("enfermeiroHistorico").value = dados.enfermeiro_nome;
    document.getElementById("especialistaHistorico").value = dados.especialidade_desc;
    document.getElementById("hgt").value = dados.hgt;
    document.getElementById("kg").value = dados.kg;
    document.getElementById("medicoHistorico").value = dados.medico_nome;
    document.getElementById("mmhg").value = dados.mmhg;
    document.getElementById("pacienteHistorico").value = dados.paciente_nome;
    document.getElementById("rpm").value = dados.rpm;
    document.getElementById("queixaSituacao").value = dados.situacao_queixa;
    document.getElementById("spo2").value = dados.spo2;
    document.getElementById("temp").value = dados.temp;
}
async function imprimirAtendimento(atend) {
    var codigo = document.getElementById(atend).value;
    if(codigo){
        window.open('/relatorio/'+codigo);
    }
    
}
async function imprimirReceita(atend) {
    var codigo = document.getElementById(atend).value;
    if(codigo){
        window.open('/receita/'+codigo);
    }
    
}
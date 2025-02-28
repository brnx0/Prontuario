
function toggleDropdown(elemeto) {
    const dropdown = document.getElementById(elemeto);
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    // document.getElementById('filterInput').focus();
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
    const codigo = document.getElementById(atend).value;
    console.log(codigo)
    if(!codigo){

    }
    window.open('/relatorio/'+codigo);
}
//     try {
//         const atend_id = 1;

//         // Fazendo a requisição para obter o PDF
//         const response = await fetch(`/relatorio/${atend_id}`, {
//             method: 'GET'
//         });

//         const data = await response.json();

//         // Criar o iframe com o PDF
//         let iframe = await criarIframe(data.pdf);
//         document.getElementById('iframePDF').contentWindow.print(); 

    

//     } catch (error) {
//         Swal.fire({
//             title: 'Aconteceu um erro ao gerar o relatório.',
//             text: error,
//             icon: 'error'
//         });
//     }
// }

// async function criarIframe(pdf) {
//     const divPai = document.getElementById('iframe');  // A div onde o iframe será inserido
//     divPai.innerHTML = '';  // Limpar conteúdo anterior do iframe

//     const iframe = document.createElement('iframe');
//     iframe.style.display = 'none';  // Deixar o iframe oculto
//     iframe.src = 'data:application/pdf;base64,' + pdf;  // Carregar o PDF base64 no iframe
//     iframe.id = "iframePDF"

//     divPai.appendChild(iframe);

//     // Retornar o iframe para ser usado após o carregamento
//     return new Promise((resolve, reject) => {
//         iframe.onload = () => resolve(iframe);  // Resolve a Promise quando o iframe carregar
//         iframe.onerror = () => reject('Erro ao carregar o iframe');  // Rejeitar caso ocorra um erro
//     });
// }

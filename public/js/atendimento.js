
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

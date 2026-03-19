@extends('principal')
@section('content')
@if (session('success'))
    <script>
        Swal.fire({
        icon: 'success',
        title: '',
        text: '{{ session('success') }}',
    });
</script>
@endif
@if (session('error'))
    <script>
        Swal.fire({
        icon: 'error',
        title: '',
        text: '{{ session('error') }}',
    });
</script>
@endif
    

<div class="container mt-4">
    {{-- <img src="{{ asset('assets/img/brasao-municipio.png') }}" class="logo" alt="Brasão"> --}}


    <form method="POST"  id="formAtendimento">
        @csrf
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="paciente" class="form-label">
                    Paciente
                    <span style="color: red;">*</span>
                </label>
                <div class="select-container">
                    <div class="custom-select" id="custom-select" onclick="toggleDropdown('dropdown')">
                        Selecione uma paciente
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown">
                        <input type="text" name="pac_cod" id="pac_cod"  hidden>
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer')"  >
                        <div class="options" id="optionsContainer">
                          
                                @foreach ($pacientes as $paciente)
                                <div class="option" onclick="selectOption(this,'{{$paciente->pac_cod}}','pac_cod','custom-select','dropdown')">{{$paciente->nome}}</div> 
                                @endforeach 
                                <div class="pagination-controls">
                                    <span id="pageInfo"></span>
                                    <i class="fa-solid fa-chevron-left" id="prevPage" onclick="prevPage('optionsContainer', 'pageInfo')"></i>
                                    <i class="fa-solid fa-chevron-right" id="nextPage" onclick="nextPage('optionsContainer', 'pageInfo')"></i>
                                </div>
                                

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label for="dtAtendimento" class="form-label">
                    Data do Atendimento
                    <span style="color: red;">*</span>
                </label>
                <input type="datetime-local" class="form-control" id="dtAtendimento" name="dtAtendimento" value="{{$data}}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-8">
                <label for="medico" class="form-label">Médico(a)</label>
                <div class="select-container">
                    <div class="custom-select" id="custom-select1"onclick="toggleDropdown('dropdown01')">
                        Selecione uma médico(a)
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown01">
                        <input type="text" name="med_cod" hidden>
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer01')" >
                        <div class="options" id="optionsContainer01">
                            @foreach ($medicos as $medico)
                                <div class="option" onclick="selectOption(this,'{{$medico->med_cod}}','med_cod','custom-select1','dropdown01')">{{$medico->med_nome}}</div> 
                            @endforeach
                            <div class="pagination-controls">
                                <span id="pageInfo"></span>
                                <i class="fa-solid fa-chevron-left" onclick="prevPage('optionsContainer01', 'pageInfo')"></i>
                                <i class="fa-solid fa-chevron-right" onclick="nextPage('optionsContainer01', 'pageInfo')"></i>
                            </div>
                    
                        </div>
                    </div>
                </div>  
            </div>
            <div class="col-md-4">
                <label for="especialidade" class="form-label">Especialidade</label>
                <div class="select-container">
                    <div class="custom-select"  id="custom-select2" onclick="toggleDropdown('dropdown02')">
                        Selecione uma especialidade
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown02">
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer02')" >
                        <input type="text" name="esp_cod" id="esp_cod" hidden>
                        <div class="options" id="optionsContainer02">
                            @foreach ($especialidades as $especialidade)
                                <div class="option" onclick="selectOption(this,'{{$especialidade->esp_cod}}','esp_cod','custom-select2','dropdown02')">{{$especialidade->escp_desc}}</div>
                            @endforeach
                            <div class="pagination-controls pagination">
                                <a class="page-link" href="#" aria-label="Previous" onclick="prevPage('optionsContainer02', 'pageInfo')">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="page-link" href="#" aria-label="Next" onclick="nextPage('optionsContainer02', 'pageInfo')">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="enfermeiro" class="form-label">Enfermeiro(a)</label>
                <div class="select-container">
                    <div class="custom-select" id="custom-select3"onclick="toggleDropdown('dropdown03')">
                        Selecione o profissional 
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div class="dropdown" id="dropdown03">
                        <input type="text" class="form-control" id="enfermeiro" name="enfermeiro" hidden>
                        <input type="text" id="filterInput" placeholder="Filtrar..." onkeyup="filterOptions(this,'optionsContainer03')" >
                        <div class="options" id="optionsContainer03">
                            @foreach ($enfermeiros as $enfermeiro)
                                <div class="option" onclick="selectOption(this,'{{$enfermeiro->enf_cod}}','enfermeiro','custom-select3','dropdown03')">{{$enfermeiro->enf_nome}}</div>  
                            @endforeach
                            <div class="pagination-controls">
                                <span id="pageInfo"></span>
                                <i class="fa-solid fa-chevron-left" onclick="prevPage('optionsContainer03', 'pageInfo')"></i>
                                <i class="fa-solid fa-chevron-right" onclick="nextPage('optionsContainer03', 'pageInfo')"></i>
                            </div>
                        </div>
                    </div>
                </div> 
                
            </div>
        </div>
        <div class="row mb-3">
            <div class="div col-md-12">
                <label for="queixaSituacao">Situação/Queixa</label>
                <input type="text" class="form-control" id="queixaSituacao" name="situacao">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="pressao" class="form-label text-nowrap">Pressão (mmHg)</label>
                <input type="text" class="form-control " id="pressao" name="mmhg">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcbpm" class="form-label text-nowrap">FC (bpm)</label>
                <input type="text" class="form-control " id="fc" name="bpm">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="fcrpm" class="form-label text-nowrap">FC (rpm)</label>
                <input type="text" class="form-control " id="fcrpm" name="rpm">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="spo2" class="form-label text-nowrap">SPO2 (%)</label>
                <input type="text" class="form-control " id="spo2" name="spo2">
            </div>
            
            <div class="col-md-1 me-4 customCol d-flex flex-column">
                <label for="temp" class="form-label text-nowrap">Temperatura &deg;C</label>
                <input type="text" class="form-control " id="temp" name="temp">
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="peso" class="form-label text-nowrap">Peso (Kg)</label>
                <input type="text" class="form-control " id="peso" name="kg">
            </div>
            
            <div class="col-sm-1 me-4 customCol d-flex flex-column">
                <label for="hgt" class="form-label text-nowrap">HGT (mg/dl)</label>
                <input type="text" class="form-control " id="hgt" name="hgt">
            </div>
        </div>
        <div class="mb-4">
            <div class="col-sm-12 d-flex flex-column">
            <label class="label form-label text-nowrap">Descrição do Caso Clínico</label>
            <textarea name="descricaoCaso" id="descricaoCaso" rows  ></textarea>
            </div>
        </div>
        <input type="text" name="receituario" id ="receituario" hidden>
        <label class="label  form-label text-noweap">Receituario </label>
        <div class="mb-4" id="editorReceituario" >
        </div>
            

        <div class="pb-2">
            <button  class="btn btn-success" onclick="salvarAtendimento()">Salvar</button>
        </div>
    </form>
</div>
</div>

<script src="{{asset('js/atendimento.js')}}"></script>

@endsection
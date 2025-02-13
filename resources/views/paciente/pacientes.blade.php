@extends('principal')
@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Pacientes</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pacienteModal" id="btnCadastro"> 
            Novo Paciente
        </button>
    </div>
    
    <!-- Filtros -->
    <div class="card p-3 mb-3">
        <form id="filterForm" method="GET" action="{{route('filtrar.paciente')}}">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="filtroNome" placeholder="Digite um nome" name="NOME">
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control" id="filtroCPF" placeholder="Digite um CPF" name="CPF">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="filtroData" name="filtroData">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" >Filtrar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabela de Registros -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Data de Nascimento</th>
                    <th>Nº Cartão SUS</th>
                    <th> Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaRegistros">
               
             @foreach ($query as $paciente)
               <tr> 
                    <td class="col-md-4">{{$paciente->NOME}}</td>
                    <td class="col-md-2">080.824.475.32</td>
                    <td class="col-md-2">{{$paciente->NASCIMENTO}}</td>
                    <td class="col-md-2">{{$paciente->SUS}}</td>
                    <td class="col-md-2"> 
                        <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#pacienteModal" 
                        data-pes_cod="{{$paciente->PES_COD}}"
                        data-nome="{{$paciente->NOME}}"
                        data-filicao_1="{{$paciente->FILICAO_1}}"
                        data-filicao_2="{{$paciente->FILICAO_2}}"
                        data-cep="{{$paciente->CEP}}"
                        data-logradouro="{{$paciente->LOGRADOURO}}"
                        data-cidade="{{$paciente->CIDADE}}"
                        data-uf="{{$paciente->UF}}"
                        data-tel_1="{{$paciente->TEL_1}}"
                        data-tel_2="{{$paciente->TEL_2}}"
                        data-email="{{$paciente->EMAIL}}"
                        data-cartao_sus="{{$paciente->CARTAO_SUS}}"
                        data-ativo="{{$paciente->Ativo}}"
                        data-prof_cod="{{$paciente->PROF_COD}}">
                        Editar
                    </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmarExclusao('{{$paciente->PES_COD}}','{{csrf_token()}}')">Excluir</button>
                    </td>
                </tr>
                
             @endforeach
            </tbody>
        </table>
        <div class=" justify-content-center">
            {{ $query->links('pagination::bootstrap-4', ['next' => 'Próximo', 'previous' => 'Anterior'])}} 
        </div>
    </div>
</div>
 @include('paciente.modalPaciente')
</script>
@if(session('open_modal'))
    <script>
     document.addEventListener('DOMContentLoaded', function() {
    var pacienteModal = new bootstrap.Modal(document.getElementById('pacienteModal'));
    pacienteModal.show(); // Abre o modal
});
    </script>
@endif


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
@endsection
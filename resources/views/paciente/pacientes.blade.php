@extends('principal')
@section('content')
<head>
    <script src="{{asset('js/paciente.js')}}"></script>
</head>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Pacientes</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pacienteModal" id="btnCadastro"> 
            <i class="fa-solid fa-plus fa-lg" style="color:rgb(245, 245, 245);"></i>
        </button>
    </div>   
    <!-- Filtros -->
    <div class="card p-3 mb-3">
        <form id="filterForm" method="GET" action="{{route('filtrar.paciente')}}">
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" id="filtroNome" placeholder="Digite um nome" name="nome">
                </div>
                <div class="col-md-3">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control" id="filtroCPF" placeholder="Digite um CPF" name="cpf">
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
                    <td class="col-md-4">{{$paciente->nome}}</td>
                    <td class="col-md-2">{{$paciente->cpf}}</td>
                    <td class="col-md-2">{{$paciente->nascimento}}</td>
                    <td class="col-md-2">{{$paciente->sus}}</td>
                    <td class="col-md-2"> 
                        @if ($paciente->ativo == 'S')
                            <button class="btn btn-sm btn-success ms-2" onclick="ativarInativarRegistro('{{$paciente->pac_cod}}','N','{{ csrf_token() }}')" title="Inativar">
                                <i class="fas fa-toggle-on fa-xl " ></i>
                            </button>
                        @else
                            <button class="btn btn-sm btn-success ms-2" onclick="ativarInativarRegistro('{{$paciente->pac_cod}}','S','{{csrf_token()}}')"title="Ativar">
                                <i class="fas fa-toggle-off fa-xl"  ></i>
                            </button>
                        @endif  
                        <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#pacienteModal"  title="Clique aqui para editar"
                            data-pes_cod="{{$paciente->pac_cod}}"
                            data-nome="{{$paciente->nome}}"
                            data-filicao_1="{{$paciente->filicao_1}}"
                            data-filicao_2="{{$paciente->filicao_2}}"
                            data-cep="{{$paciente->cep}}"
                            data-logradouro="{{$paciente->logradouro}}"
                            data-cidade="{{$paciente->cidade}}"
                            data-uf="{{$paciente->uf}}"
                            data-tel_1="{{$paciente->tel_1}}"
                            data-tel_2="{{$paciente->tel_2}}"
                            data-email="{{$paciente->email}}"
                            data-cartao_sus="{{$paciente->cartao_sus}}"
                            data-ativo="{{$paciente->ativo}}"
                            data-prof_cod="{{$paciente->prof_cod}}">
                            <i class="fa-solid fa-pen-to-square fa-xl" style="color: #ffffff;"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmarExclusao('{{$paciente->pac_cod}}','{{csrf_token()}}')">
                            <i class="fa-solid fa-trash fa-xl" style="color: #ffffff;"></i>
                        </button>
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
 <form id="editStatusPaciente" method="POST">
    @csrf
    @method('PUT') 
    <input type="text" name="pac_cod" id="pac_cod" hidden>
    <input type="text" name="status" id="status" hidden>
</form>
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
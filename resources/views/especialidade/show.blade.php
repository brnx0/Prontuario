@extends('principal') 
@section('content')
<head>
    <script src="{{asset('js/especialidade.js')}}"></script>
</head>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Especialidades</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#especialidadeModal">
            <i class="fa-solid fa-plus fa-lg" style="color:rgb(245, 245, 245);"></i>
        </button>
    </div>
    
    <!-- Filtros -->
    <div class="card p-3 mb-3">
        <form id="filterForm" method="GET" action="{{route('filtrarEspecialidade')}}">
            <div class="row g-2">
                <div class="col-md-10">
                    <label class="form-label">Especialidade</label>
                    <input type="text" class="form-control" id="filtroNome" placeholder="Digite uma especialidade" name="escp_desc">
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
                    <th>Especialidade</th>
                    <th>Status</th>
                    <th> Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaRegistros">
               
                @foreach ($especialidades as $especialidade)
                    <tr> 
                        <td class="col-md-6">{{$especialidade->escp_desc}}</td>
                        <td class="col-md-2">{{$especialidade->ativo == 'S' ? 'Ativo':'Inativo';}}</td>
                        <td class="col-md-2">
                            @if ($especialidade->ativo == 'S')
                                <button class="btn btn-sm btn-success ms-2" onclick="ativarInativarEspc('{{$especialidade->esp_cod}}','N','{{ csrf_token() }}')">
                                    <i class="fas fa-toggle-on fa-xl " ></i><!-- Desativar -->
                                </button>
                            @else
                                <button class="btn btn-sm btn-danger ms-2" onclick="ativarInativarEspc('{{$especialidade->esp_cod}}','S','{{csrf_token()}}')">
                                    <i class="fas fa-toggle-off fa-xl"></i>   <!-- Ativar -->
                                </button>
                            @endif
                            
                            <button class="btn btn-sm btn-warning edit-btn ms-2" data-bs-toggle="modal" data-bs-target="#especialidadeModal"> 
                                <i class="fa-solid fa-pen-to-square fa-xl" style="color: #ffffff;"></i><!-- Editar -->
                            </button>
                            <button class="btn btn-sm btn-danger ms-2" onclick="excluirMedico('{{$especialidade->esp_cod}}')">
                                <i class="fa-solid fa-trash fa-xl" style="color: #ffffff;"></i><!-- Excluir -->
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form id="editStatusEspecialidade" method="POST">
            @csrf
            @method('PUT') 
            <input type="text" name="espc_cod" id="espc_cod" hidden>
            <input type="text" name="status" id="status" hidden>
            

        </form>
        <form  id="deletEspecialidade" method="POST" >
            @csrf
            @method('DELETE') 
            <input type="text" name="espc_cod" id="MED_COD" hidden>
        </form>
        <div class=" justify-content-center">
            
        </div>
    </div>
</div>
{{ $especialidades->links('pagination::bootstrap-4', ['next' => 'Próximo', 'previous' => 'Anterior'])}}
@include('especialidade.form');


@endsection
